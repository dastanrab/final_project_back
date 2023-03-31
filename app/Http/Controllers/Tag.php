<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagRules;
use App\Http\Requests\updateTag;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use App\Models\Tag as T;

use App\Models\Product;
use Intervention\Image\Facades\Image;

class Tag extends Controller
{
    /**
     * @OA\GET(
     * path="tag/",
     * summary="tags",
     * description="get tags",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"phone"},
     *       @OA\Property(property="phone", type="string", format="text", example="09388985617"),
     *
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    public function index(): string
    {


        if (T::has('product')->first()){

            return response()->json(T::with(['product'])->get());
        }
        else{
            return response()->json('false');
        }

    }
    public function add_show(){
        $countries=new Country();
        $countries=$countries->setVisible(['id','name']);
        $countries=$countries->select(['id','name'])->get();
        $tags_name=new T();
        $tags_name=$tags_name->select('name')->distinct()->get();
        return response()->json(['country'=>$countries,'tag'=>$tags_name]);
    }
    public function add(TagRules $request){

      if(!T::where('name',$request->input('name'))->where('country_id',$request->input('country_id'))->first()){
          if($request->hasFile('image')){
              $file=$request->file('image');
              $name=time().".".$file->extension();
              $img=Image::make($file->getRealPath());
              $img->resize(400, 400, function ($constraint) {
                  $constraint->aspectRatio();
              });
              if(Storage::disk('local')->put('/tags/'.$name,  (string) $img->encode(), 'public')){

                  if ($tag = T::create([
                      'name' => $request->input('name'),
                      'country_id'=>$request->input('country_id'),
                      'image'=> '/tags/'.$name
                  ])){
                      return response()->json(['status'=>'ذخیره شد','tag'=>$tag]);
                  }
                  else{
                      return response()->json(['status'=>'خطا در ذخیره سازی']);
                  }

              }
              else{
                  return response()->json('خطا در ذخیره سازی');
              }
          }

      }
      else{
          return response()->json("its exist");
      }
    }
    /**
     * @OA\Post(
     *      path="/v1/order/edit/{{barcode}}",
     *      operationId="updateorder",
     *      tags={"order"},
     *      summary="ویرایش سفارش",
     *      description="edit order",
     *      security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"phone"},
     *       @OA\Property(property="barcode", type="string", format="text", example="09388985617"),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function destroy($id = null): JsonResponse
    {
        if ($id==null)
        {
            return response()->json('لطفا id مورد نظر را باور کنید');
        }
        else{
            $tag=T::find($id);
            if($tag){
                $product=Product::where('tag_id','=',$id)->first();
                if ($product){
                    return response()->json("این دسته در چند محصول استفاده شده و قابل حذف نیست");
                }
                else{
                    if(Storage::disk('local')->exists($tag->image))
                    {
                        if (Storage::disk('local')->delete($tag->image))
                        {
                            if ($tag->delete() ){

                                return response()->json("حذف شد");
                            }
                            else{
                                return response()->json("اشکال در حذف از جدول");
                            }

                        }
                        else{

                            return response()->json("اشکال در حذف فایل");
                        }
                    }
                    else{
                        if ( $tag->delete() ){
                            return response()->json("حذف شد");
                        }
                        else{
                            return response()->json("اشکال در حذف از جدول");
                        }
                    }
                }


            }
            else{
                return response()->json("مورد یافت نشد");
            }
        }

    }
    /**
     * @OA\Post(
     *      path="/v1/order/register",
     *      operationId="addorder",
     *      tags={"order"},
     *      summary="ثبت سفارش",
     *      security={{"oauth2": {"*"}}},
     *      description="User Register here",
     *     @OA\Parameter(
     *         name="x-api-key",
     *         in="header",
     *         description="api token",
     *         required=true
     *     ),
     *     @OA\RequestBody(
     *       @OA\JsonContent( required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="persistent", type="boolean", example="true"),
     *     ),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password", "password_confirmation"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function update(updateTag $request){
        $tag=T::find($request->input('id'));
        if ($tag){

            if ($request->input('name')!=null or $request->input('name')!=0 ){
                $tag->name=$request->input('name');
            }
            if ( $request->input('country_id')!=null or $request->input('country_id')!=0 ){
                $tag->country_id=$request->input('country_id');
            }
            $find=T::where('name',$tag->name)->where('country_id',$tag->country_id)->first();
            if(!$find or $find->id==$tag->id){
                if ($request->hasFile('image')){
                    if(Storage::disk('local')->exists($tag->image))
                    {
                        if (Storage::disk('local')->delete($tag->image))
                        {
                            $file=$request->file('image');
                            $name=time().".".$file->extension();
                            $img=Image::make($file->getRealPath());
                            $img->resize(400, 400, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            //->stream()
                            if(Storage::disk('local')->put('/tags/'.$name,  (string) $img->encode(), 'public')){
                                $tag->image='/tags/'.$name;
                            }

                        }
                        else{

                            return response()->json("اشکال در حذف فایل");
                        }
                    }
                    else{
                        $file=$request->file('image');
                        $name=time().".".$file->extension();
                        $img=Image::make($file->getRealPath());
                        $img->resize(400, 400, function ($constraint) {
                            $constraint->aspectRatio();
                        })->stream();
                        if(Storage::disk('local')->put('/tags/'.$name, $img, 'public')){
                            $tag->image='/tags/'.$name;
                        }
                    }
                }

                if ($tag->save()){

                    return response()->json("انجام شد") ;
                }
            }
            else{
                return response()->json("این مورد موجود است") ;
            }

        }
        else{

            return response()->json("دسته بندی مورد نظز یافت نشد");
        }


    }
}

