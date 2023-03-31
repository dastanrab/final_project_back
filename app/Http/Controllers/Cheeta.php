<?php


namespace App\Http\Controllers;


class Cheeta
{
    /**
     * @OA\Post(
     *      path="/v1/order/edit/{{barcode}}/product",
     *      operationId="updateorderحقخیعزف",
     *      tags={"order"},
     *      summary="ویرایش  محصول یک سفارش",
     *      description="edit order",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
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
  public function one(){

  }
    /**
     * @OA\Post(
     *      path="/api/v1/order/{{barcode}}/ready",
     *      operationId="changestatustoready",
     *      tags={"order"},
     *      summary="تغییر وضعیت سفارش به اماده",
     *      description="edit order",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="int", format="number", example="09388985617"),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
  public function two(){

  }
    /**
     * @OA\Post(
     *      path="/api/v1/order/{{barcode}}/suspend",
     *      operationId="changestatustosuspend",
     *      tags={"order"},
     *      summary="تغییر وضعیت سفارش به معلق",
     *      description="suspend order",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="int", format="number", example="09388985617"),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function three(){

    }
    /**
     * @OA\Post(
     *      path="/v1/order/prices",
     *      operationId="getorderprice",
     *      tags={"order"},
     *      summary="محاسبه قیمت سفارش",
     *      description="edit order",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="int", format="number", example="09388985617"),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function four(){

    }
    /**
     * @OA\Post(
     *      path="v1/order/status",
     *      operationId="getstatus",
     *      tags={"order"},
     *      summary="لیست وضعیت سفارش های ارسالی",
     *      description="get orders status",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *      @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function five(){

    }
    /**
     *  @OA\Get(
     *      path="/order/status/changes/{date}",
     *        operationId="getstatuschangedate",
     *      tags={"order"},
     *      summary=" لیست تغییرات سفارش با تاریخ",
     *      description="دریافت لیست تغییرات سفارش بر اساس تاریخ",
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      @OA\Parameter(
     *         name="date",
     *         in="path",
     *         description="date of change time",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="Ha ocurrido un error."
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="No se ha autenticado, ingrese el token."
     *      ),
     *  )
     */
    public function six(){

    }
    /**
     * @OA\Post(
     *      path="/v1/order/bulk/ready",
     *      operationId="groupstatusready",
     *      tags={"order"},
     *      summary="تغییر وضعیت گروهی به اماده",
     *      description="change group of orders status to ready",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *      @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function seven(){

    }
    /**
     * @OA\Post(
     *      path="v1/order/bulk/suspend",
     *      operationId="groupstatussuspend",
     *      tags={"order"},
     *      summary="تغییر وضعیت گروهی به معلق",
     *      description="change group of orders status to suspend",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *      @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function eight(){

    }
    /**
     * @OA\Post(
     *      path="/v1/order/financial",
     *      operationId="getorder financial",
     *      tags={"order"},
     *      summary="لیست مالی سفارشات",
     *      description="get orders financial",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *      @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function nine(){

    }
    /**
     * @OA\Post(
     *      path="/v1/bag/create",
     *      operationId="bagcreate",
     *      tags={"bag"},
     *      summary="ایجاد کیسه",
     *      description="create bag for given orders barcode",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *     @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function ten(){

    }
    /**
     * @OA\Post(
     *      path="/v1/bag/check",
     *      operationId="bagcheck",
     *      tags={"bag"},
     *      summary="بررسی کیسه",
     *      description="check bag for given orders barcode",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *     @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function eleven(){

    }
    /**
     * @OA\Post(
     *      path="/v1/distribute/deliver",
     *      operationId="distributechangedeliver",
     *      tags={"distribute"},
     *      summary="تغییر وضعیت لیست توزیع",
     *      description="change distribute orders status ",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"barcode"},
     *       @OA\Property(property="barcode", type="array",
     * *     @OA\Items(
     *               type="number",
     *               description="order barcodes",
     *               @OA\Schema(type="number")
     *         )),
     *
     *    ),
     * ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function twelve(){

    }
    /**
     * @OA\Get(
     *      path="/v1/collect/receive",
     *      operationId="listcollect",
     *      tags={"collect"},
     *      summary="تغییر وضعیت لیست توزیع",
     *      description="change distribute orders status ",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       )
     *     )
     */
    public function thirteen(){

    }
    /**
     *  @OA\Get(
     *      path="/v1/order/quick_search/{barcode}",
     *        operationId="searchorder",
     *      tags={"order"},
     *      summary=" جستجو سفارش با بارکد",
     *      description="گرفتن سفارش با شماره بارکد",
     *      security={
     *          {"bearer_token":{}},
     *      },
     *      @OA\Parameter(
     *         name="date",
     *         in="path",
     *         description="date of chanکدge time",
     *         required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response="default",
     *          description="Ha ocurrido un error."
     *      ),
     *      @OA\Response(
     *          response="401",
     *          description="No se ha autenticado, ingrese el token."
     *      ),
     *     security={
     *         {"bearer": {}}
     *     }
     *  )
     */
    public function fourteen(){

    }

}
