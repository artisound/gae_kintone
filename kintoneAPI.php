<?php

class tcLibKintone {

  /*************************************************************************/
  /* メンバ変数(プロパティ)                                                */
  /*************************************************************************/
  protected $subdomain;
  protected $login_id;
  protected $password;

  /*************************************************************************/
  /* コンストラクタ                                                        */
  /*************************************************************************/
  public function __construct($subdomain, $login_id, $password)	{
		$this->subdomain  = $subdomain;
		$this->login_id   = $login_id;
		$this->password   = $password;
	}

  /*************************************************************************/
  /* メソッド                                                              */
  /*************************************************************************/
  private function kintoneAPI_init() {
    return new \CybozuHttp\Api\KintoneApi(new \CybozuHttp\Client([
      'domain'    => 'cybozu.com',
      'subdomain' => $this->subdomain,
      'login'     => $this->login_id,
      'password'  => $this->password,
    ]));
  }

  // -----------------------------------------------------------------------
  // レコード情報を取得する
  //  - カーソルを使ったレコード取得
  // -----------------------------------------------------------------------
  public function cursorAll ($appId, $query, $pFields = null) {
      // GET
      $api = $this->kintoneAPI_init();

      $query = $query;

      // API実行
      try {
          if($pFields){
              $ret = $api->cursor()->all($appId, $query, $pFields);
          } else {
              $ret = $api->cursor()->all($appId, $query);
          }
          return $ret;
      } catch (Exception $e) {
          // APIエラー時
          die($e->getMessage());

          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }

//        return $ret;
  }


  // -----------------------------------------------------------------------
  // レコード情報を取得する
  //  - カーソルを使ったレコード取得
  // -----------------------------------------------------------------------
  public function getRecords ($appId, $query) {
      // GET
      $api = $this->kintoneAPI_init();


      // API実行
      try {
          $ret = $api->records()->get($appId, $query);
          return $ret['records'];
      } catch (Exception $e) {
          // APIエラー時
          die($e->getMessage());

          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }

//        return $ret;
  }


  // -----------------------------------------------------------------------
  // フィールド情報を取得する
  //  - カーソルを使ったレコード取得
  // -----------------------------------------------------------------------
  public function getFields ($appId, $query) {
      // GET
      $api = $this->kintoneAPI_init();

      // API実行
      try {
          $ret = $api->records()->get($appId, $query);
          // print_r($rec);
          return $ret['records'][0];
      } catch (Exception $e) {
          // APIエラー時
          die($e->getMessage());

          // エラーログを出力
          //ファイルを指定
          
          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }

//        return $ret;
  }

  // -----------------------------------------------------------------------
  // レコードを1件更新する
  // -----------------------------------------------------------------------
  public function putRec ($appId, $pRecId, $pRecVal) {
      // PUT
      $api = $this->kintoneAPI_init();

      // APIパラメータを設定する
      $recPrm = [];
      foreach ( $pRecVal as $key => $prm ) {
          $recPrm[$key] = new stdClass();
          $recPrm[$key] = $prm;
      }

      // API実行
      try {
          return $api->record()->put($appId, $pRecId, $recPrm);
      } catch (Exception $e) {
          // APIエラー時
          die($e->getMessage());

          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }
  }


  // -----------------------------------------------------------------------
  // レコードを複数件更新する
  // -----------------------------------------------------------------------
  public function putRecords ($appId, $pRecVals) {

      $ret = 0;

      // POST
      $api = $this->kintoneAPI_init();

      // APIパラメータを設定する
      $recPrm = [];

      foreach ( $pRecVals as $key => $prm ) {

          // 空配列対策
          //  - 初期組み込み版では、ajax経由で渡された更新パラメータの内配列の中身が空の場合、更新されないので対策(20210428)
          //    最終的にはnode.js化してこのファイル自体不要にしたい
          foreach ( $prm['record'] as $fCode => $fVal ) {
              if ( is_array($fVal['value']) == true ) {
                  foreach ( $fVal['value'] as $aryCnt => $aryVal ) {
                      if ( $aryVal == "" ) { $prm['record'][$fCode]['value'] = []; }
                  }
              }
          }

          $recPrm[$key] = new stdClass();
          $recPrm[$key] = $prm;
      }

      // API実行
      try {
          $ret = $api->records()->put($appId, $recPrm);
      } catch (Exception $e) {

          // APIエラー時
          die($e->getMessage());

          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }
      return $ret;
  }

  // -----------------------------------------------------------------------
  // レコードを1件登録する
  // -----------------------------------------------------------------------
  public function postRec ($appId, $pRecVal) {

      $ret = 0;

      // POST
      $api = $this->kintoneAPI_init();

      // APIパラメータを設定する
      $recPrm = [];

      foreach ( $pRecVal as $key => $prm ) {
          $recPrm[$key] = new stdClass();
          $recPrm[$key] = $prm;
      }

      // API実行
      try {
          $ret = $api->record()->post($appId, $recPrm);
      } catch (Exception $e) {

          // APIエラー時
          die($e->getMessage());

          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }
      return $ret;
  }


  // -----------------------------------------------------------------------
  // レコードを複数件登録する
  // -----------------------------------------------------------------------
  public function postRecords ($appId, $pRecVals) {

      $ret = 0;

      // POST
      $api = $this->kintoneAPI_init();

      // APIパラメータを設定する
      $recPrm = [];

      foreach ( $pRecVals as $key => $prm ) {
          $recPrm[$key] = new stdClass();
          $recPrm[$key] = $prm;
      }

      // API実行
      try {
          $ret = $api->records()->post($appId, $recPrm);
      } catch (Exception $e) {

          // APIエラー時
          die($e->getMessage());

          // 時刻を指定
          $log_time = date('Y-m-d H:i:s');
          error_log('['.$log_time.'] '.print_r($e->getMessage(),true).PHP_EOL, 3, $log);

      }
      return $ret;
  }


  // -----------------------------------------------------------------------
  // kintoneにファイルをアップロードする
  //  - アップロードしたファイルキーを返却
  // -----------------------------------------------------------------------
  public function uploadFile ( $pFilePass ) {

      $api = $this->kintoneAPI_init();

      $key = $api->file()->post($pFilePass);
      return $key;

  }


  // -----------------------------------------------------------------------
  // kintoneにファイル複数をアップロードする
  //  - アップロードしたファイルキーを返却
  // -----------------------------------------------------------------------
  public function uploadFiles ( $pAryFilePass ) {

      $api = $this->kintoneAPI_init();

      $keys = $api->file()->multiPost($pAryFilePass);
      return $keys;

  }


  // -----------------------------------------------------------------------
  // kintoneにファイルをダウンロードする
  //  - 
  // -----------------------------------------------------------------------
  public function getFile ( $pFileKey ) {

      $api = $this->kintoneAPI_init();

      $keys = $api->file()->get($pFileKey);
      return $keys;

  }


  // -----------------------------------------------------------------------
  // kintoneにファイル複数をダウンロードする
  //  - 
  // -----------------------------------------------------------------------
  public function getFiles ( $pAryFileKey ) {

      $api = $this->kintoneAPI_init();

      $keys = $api->file()->multiGet($pAryFileKey);
      return $keys;

  }


  // -----------------------------------------------------------------------
  // フィールド情報を取得する
  // -----------------------------------------------------------------------
  public function getFieldCodes ( $appId ) {

      $api = $this->kintoneAPI_init();

      $keys = $api->fields()->get($appId);
      return $keys;

  }

}	// end class