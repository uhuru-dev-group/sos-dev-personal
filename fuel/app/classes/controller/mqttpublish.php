<?php

require_once(APPPATH . 'modules/phpMQTT.php');

/**
 *
 * @task 小林
 * 共通テンプレを使って画面の行き来を可能にしたい
 * http://qiita.com/kiimiiis/items/a015399455ef9c0f1659
 */
class Controller_Mqttpublish extends Controller
{
    public $_viewBasePath = 'mqttpublish/';
    public $_endpoint_base;
    public $_db_url;
    public $_xi_username;
    public $_xi_password;
    public $_printer_serial; // シリアル番号
    public $_regist_type; // 登録種別

    /**
     * GET
     * @return type
     */
	public function get_index()
	{
        $viewPath = $this->_viewBasePath . 'index';
		return Response::forge(View::forge($viewPath));
	}

    /**
     * POST
     */
    public function post_index()
    {
        $viewPath = $this->_viewBasePath . 'index';

        // 登録先の環境情報取得
        $regist_env = Input::post('regist_env', '');
        if(!empty($regist_env)) {
            $this->set_sos_config($regist_env);
        }

        $this->_regist_type = Input::post('regist_type', '');
        $this->_printer_serial = Input::post('printer_serial', '');

        // 種別があれば分岐
        if($this->_regist_type != '') {
            switch($this->_regist_type) {
                case 'serial' :
                    // シリアル登録
                    $this->registPrinterSerial();
                    break;
                case 'printer' :
                    // 仮想プリンタ登録
                    $this->registVirtualPrinter();
                    break;
                default :
                    // 何もしない
                    break;
            }
        }

		return Response::forge(View::forge($viewPath));
    }


    /**
     * 指定した環境のbackendの情報を設定します
     *
     * @param type $regist_env
     */
    public function set_sos_config($regist_env)
    {
        $endPoints = Config::get('sosConf.endpoint_base');
        $db_urls = Config::get('sosConf.db_url');
        $this->_endpoint_base = (isset($endPoints[$regist_env])) ? $endPoints[$regist_env] : null;
        $this->_db_url = (isset($db_urls[$regist_env])) ? $db_urls[$regist_env] : null;
    }

    /**
     * プリンタ登録
     *
     * @return type
     */
    public function regist_data()
    {
        $url = $this->_endpoint_base . '/v1/printers/manufactured';
        $content = json_encode([
            "model" => "SATO FRONTEND END-TO-END TEST",
            "manufacture_key" => Config::get('sosConf.manufacture_key'),
            "new_devices" => [
                "device_serial" => $this->_printer_serial,
            ],
            "agent_version" => "dev:d70c5f22a166788de7c7b5663748d3e59ec8c51f",
        ]);
        $headers = ['Content-type: application/json'];

        return $this->curl_exec($url, $headers, $content);
    }

    /**
     *  ザイブリーに登録
     */
    public function regist_xivlely()
    {
        $url = $this->_endpoint_base . '/v1/printers/create';
        $content = json_encode([
            "device_serial" => $this->_printer_serial,
            "agent_version" => "0.1.0",
            "creation_key" => Config::get('sosConf.creation_key'),
            "model" => "dev:d70c5f22a166788de7c7b5663748d3e59ec8c51f",
        ]);
        $headers = ['Content-type: application/json'];
        return $this->curl_exec($url, $headers, $content, 'PUT');
    }

    /**
     *  チャレンジコード
     */
    public function set_challenge_code()
    {
        $url = $this->_endpoint_base . '/v1/printers/challenge_code';
        $content = json_encode([
            "printer_serial" => $this->_printer_serial,
            "xively_username" => $this->_xi_username,
            "xively_password" => $this->_xi_password,
        ]);
        $headers = ['Content-type: application/json'];
        return $this->curl_exec($url, $headers, $content);
    }

    /**
     * シリアルから対象のレコード情報を取得
     */
    public function find_asset()
    {
        // メッセージ切り替え
        $typeMessage = '';
        switch($this->_regist_type) {
            case 'serial' :
                $typeMessage = 'シリアル登録';
                break;
            case 'printer' :
                // 仮想プリンタ登録
                $typeMessage = '仮想プリンタ登録';
                break;
            default :
                // 何もしない
                break;
        }


        $url = parse_url($this->_db_url);
        $dsn = sprintf('pgsql:host=%s;dbname=%s port=%s', $url['host'], substr($url['path'], 1), $url['port']);
        $pdo = new \PDO($dsn, $url['user'], $url['pass']);
        $stmt = $pdo->query(
            'SELECT * FROM salesforce.asset '
            . 'WHERE printer_serial__c = \'' . $this->_printer_serial . '\''
        );
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            echo $typeMessage;
            echo "<br>";
            echo 'シリアル = ' . $row['serialnumber'];
            echo "<br>";
            echo 'アソシエーションコード = ' . $row['association_code__c'];
            echo "<br>";
        }
        unset($pdo);
    }

    /**
     * シリアル登録
     */
    public function registPrinterSerial()
    {
        try
        {
            // データ登録
            $this->regist_data();

            // 対象レコードのアソシエーションコード取得
            $this->find_asset();
        }
        catch (\Exception $e)
        {
            var_dump($e);
        }
    }


    /**
     * 仮想プリンタ追加
     */
    public function registVirtualPrinter()
    {
        try
        {
            // データ登録
            $this->regist_data();

            // ザイブリーに登録
            $resultJson = $this->regist_xivlely();
            $resultArray = json_decode($resultJson, true);
            $this->_xi_username = $resultArray['xi_username'];
            $this->_xi_password = $resultArray['xi_password'];

            // アソシエーション(ChallengeCode)
            $this->set_challenge_code();

            // 対象レコードのアソシエーションコード取得
            $this->find_asset();
        }
        catch (\Exception $e)
        {
            var_dump($e);
        }
    }

    /**
     * リクエストを投げるやつ
     *
     * @param type $url
     * @param type $headers
     * @param type $content
     * @return type
     */
    public function curl_exec($url, $headers, $content, $method='POST')
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}
