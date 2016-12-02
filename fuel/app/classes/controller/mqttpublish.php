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
    public $_printer_serial;

    /**
     * GET
     * @return type
     */
	public function get_index()
	{
        // http://mqtt_pub.com/?mqtt=1
        if(Input::get('mqtt')) {
           $this->send_mqtt();
        }

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

        // シリアルがあったら仮想プリンタを登録
        $this->_printer_serial = Input::post('printer_serial', '');
        $this->registVirtualPrinter();
		return Response::forge(View::forge($viewPath));
    }

    /**
     * mqttブローカーへメッセージを投げる
     */
	public function send_mqtt()
    {
//echo "exec mqtt send" . "<br>";

		try
        {

$clean = true;
$will = NULL;
$username = "b44ac8a6-3178-4bdf-aa95-685a48527873";
$password = "liccw4mgl0lrf3ipdfDUe3g9V5SHrA0o0H2i6CYmzx4=";

            // configに入れる（4BBBBBBB）
			$mqtt_host = "sato.broker.xively.com"; // MQTT ブローカー
            $mqtt_clientid = $username; // クライアントID
            $mqtt_port = 8883; // MQTT ポート番号
            $mqtt = new phpMQTT($mqtt_host, $mqtt_port, $mqtt_clientid);

//var_dump($mqtt); die;
echo "try mqtt connect" . "<br>";

//            $mqtt->debug = true; // debugモード
			if($mqtt->connect($clean, $will, $username, $password))
//			if($mqtt->connect())
            {
echo "try mqtt publish" . "<br>";
                // 変数化する
                $mqtt_topic = "xi/blue/v1/eb309b2d-5a8d-495c-b73d-9f69978747d4/d/b44ac8a6-3178-4bdf-aa95-685a48527873/RPCreq"; // トピック文字列
                $mqtt_message = '{"method": "set_settings", "params": [{"id":"cat.short/configTbl.pdd.speed","value":8}], "id": 61192}'; # パブリッシュするメッセージ

				$mqtt->publish($mqtt_topic, $mqtt_message, 0);
				$mqtt->close();
echo "end mqtt publish" . "<br>";
			}
            else
            {
echo "conect false!!";
			}
		}
        catch (Exception $e)
        {
			var_dump($e);
		}
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
        $url = parse_url($this->_db_url);
        $dsn = sprintf('pgsql:host=%s;dbname=%s port=%s', $url['host'], substr($url['path'], 1), $url['port']);
        $pdo = new \PDO($dsn, $url['user'], $url['pass']);
        $stmt = $pdo->query(
            'SELECT * FROM salesforce.asset '
            . 'WHERE printer_serial__c = \'' . $this->_printer_serial . '\''
        );
        $result = $stmt->fetchAll();
        foreach ($result as $row) {
            echo 'シリアル = ' . $row['serialnumber'];
            echo "\n";
            echo 'アソシエーションコード = ' . $row['association_code__c'];
            echo "\n";
        }
        unset($pdo);
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
