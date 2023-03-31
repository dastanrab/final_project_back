<?php


namespace App\Services\Cart;


use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;

class CartService
{
    private $ttl = 1800;
    private Product $product;

    public function __construct()
    {
        $this->product = new Product();
        $this->now = Carbon::now();
    }

    public function show()
    {

        $ip = request()->ip();
        if (!Redis::exists($ip)) {
            return $this->responseMassage(false, ['empty cart']);

        } else {
            $data = unserialize(Redis::get($ip));
            $basket = [];
            $total = 0;

            if (!is_null($data)) {
                $order_ids = array_keys($data);
                $finded_order_ids = [];
                $products = $this->CartProduct($order_ids);

                foreach ($products as $product) {
                    if ($product) {
                        $collect_product = collect($product);
                        $count = $data[$product->id];
                        $finded_order_ids[] = $product->id;
                        $price = is_null($product->offer) ? $product->price : ($product->price * (100 - $product->offer->off)) / 100;
                        $item = $collect_product->put('quantity', $count)->put('price_with_off', $price)->put('total', $product->price * $count);
                        $total += ($price * $count);
                        array_push($basket, $item);
                    }
                }
                $unfinded_orders = array_diff($order_ids, $finded_order_ids);
                return $this->responseMassage(true, ['basket' => $basket, 'total' => $total, 'unfinded' => $unfinded_orders]);

            } else {
                return $this->responseMassage(false, ['msg' => 'empty cart']);

            }
        }

    }

    private function responseMassage(bool $status, array $data)
    {
        return response()->json(['status' => $status, 'data' => $data]);
    }

    private function CartProduct(array $ids)
    {
        return $this->product::with(['offer' => function ($q) {
            $q->where('finish', '<', $this->now->toDateTimeString());
        }])->whereIn('id', $ids)->get();
    }

    public function increase($id)
    {
        $ip = request()->ip();
        if (!is_null($ip)) {
            if ($this->product::find($id)) {
                if (!Redis::exists($ip)) {
                    $basket = [];
                    $basket[$id] = 1;
                    Redis::setex($ip, $this->ttl, serialize($basket)
                    );
                    Redis::bgSave();
                    return $this->responseMassage(false, ['msg' => 'add to cart']);


                } else {
                    $arr = unserialize(Redis::get($ip));
                    if (isset($arr[$id])) {
                        $arr[$id]++;
                        Redis::setex($ip, $this->ttl, serialize($arr));
                        Redis::bgSave();
                        return $this->responseMassage(false, ['msg' => 'add to order count']);

                    } else {
                        $arr[$id] = 1;
                        Redis::setex($ip, $this->ttl, serialize($arr)
                        );
                        Redis::bgSave();
                        return $this->responseMassage(false, ['msg' => 'add to cart']);

                    }

                }
            } else {
                if (Redis::exists($ip)) {
                    $arr = unserialize(Redis::get($ip));
                    if (isset($arr[$id])) {
                        unset($arr[$id]);
                        if (count($arr) <= 0) {
                            Redis::del($ip);
                            return $this->responseMassage(false, ['msg' => 'cart']);

                        } else {
                            Redis::setex($ip, $this->ttl, serialize($arr)
                            );
                            Redis::bgSave();
                            return $this->responseMassage(false, ['msg' => 'nothing found and update cart']);

                        }
                    } else {
                        return $this->responseMassage(false, ['msg' => 'nothing found']);

                    }
                } else {
                    return $this->responseMassage(false, ['msg' => 'nothing found']);

                }
            }
        } else {
            return $this->responseMassage(false, ['msg' => 'wrong ip']);

        }

    }

    public function decrements($id)
    {
        $ip = request()->ip();
        if (!is_null($ip)) {
            if (Product::find($id)) {
                if (!Redis::exists($ip)) {
                    return $this->responseMassage(false, ['msg' => 'no cart']);

                } else {
                    $basket = unserialize(Redis::get($ip));
                    if (isset($basket[$id])) {
                        $basket[$id]--;
                        if ($basket[$id] <= 0) {
                            unset($basket[$id]);
                            if (count($basket) <= 0) {
                                Redis::del($ip);
                                return $this->responseMassage(false, ['msg' => 'empty cart']);

                            } else {
                                Redis::setex($ip, $this->ttl, serialize($basket)
                                );
                                Redis::bgSave();
                                return $this->responseMassage(true, ['msg' => 'order decrease']);

                            }
                        } else {
                            Redis::setex($ip, $this->ttl, serialize($basket)
                            );
                            Redis::bgSave();
                            return $this->responseMassage(true, ['msg' => 'order decrease']);

                        }

                    } else {
                        return $this->responseMassage(false, ['msg' => 'nothing found']);

                    }
                }
            } else {
                if (Redis::exists($ip)) {
                    $arr = unserialize(Redis::get($ip));
                    if (isset($arr[$id])) {
                        unset($arr[$id]);
                        if (count($arr) <= 0) {
                            Redis::del($ip);
                            return $this->responseMassage(false, ['msg' => 'empty cart']);

                        } else {
                            Redis::setex($ip, $this->ttl, serialize($arr)
                            );
                            Redis::bgSave();
                            return $this->responseMassage(false, ['msg' => 'cart update']);

                        }
                    } else {
                        return $this->responseMassage(false, ['msg' => 'nothing found']);

                    }
                } else {
                    return $this->responseMassage(false, ['msg' => 'nothing found in cart']);

                }
            }
        } else {
            return $this->responseMassage(false, ['msg' => 'incorrect ip']);
        }
    }

    public function flush()
    {
        $ip = request()->ip();
        if (!is_null($ip)) {
            if (Redis::exists($ip)) {
                Redis::del($ip);
                return $this->responseMassage(false, ['msg' => 'سبد شما خالی است']);

            } else {
                return $this->responseMassage(false, ['msg' => 'شما سبدی ندارید']);
            }

        } else {
            return $this->responseMassage(false, ['msg' => 'ای پی شما مشخص نیست']);

        }

    }

    public function delete($id)
    {
        $ip = request()->ip();
        if (!is_null($ip)) {

            if (!Redis::exists($ip)) {

                return $this->responseMassage(false, ['msg' => 'شما سبدی ندارید']);

            } else {
                $basket = unserialize(Redis::get($ip));
                if (isset($basket[$id])) {
                    unset($basket[$id]);
                    if (count($basket) <= 0) {
                        Redis::del($ip);
                        return $this->responseMassage(false, ['msg' => 'سبد شما خالی است']);

                    } else {
                        Redis::setex($ip, $this->ttl, serialize($basket)
                        );
                        Redis::bgSave();
                        return $this->responseMassage(true, ['msg' => 'محصول مورد نظر حذف شد']);
                    }
                } else {
                    return $this->responseMassage(false, ['msg' => 'محصول یافت نشد']);
                }
            }

        } else {
            return $this->responseMassage(false, ['msg' => 'ای پی شما مشخص نیست']);
        }
    }
}
