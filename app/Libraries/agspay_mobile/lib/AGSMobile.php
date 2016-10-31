<?php
	require_once("JSONFunc.php");

	define('AGSHOST','https://www.allthegate.com');

	class AGSMobile {

		var $tracking_id = "";
		var $transaction = "";
		var $store_id = "";
		var $tracking_info = array();
		var $logging = false;
		var $logfile = null;
		var $log_path = null;
		var $ispCardNm = "";
		var $netCancelId = "";

		function AGSMobile() {
			$args= func_get_args();
	        call_user_func_array
	        (
	            array(&$this, '__construct'),
	            $args
	        );


		}

		function __construct($store_id , $tracking_id , $transaction, $log_path) {
			$this->store_id = $store_id;
			$this->tracking_id = $tracking_id;
			$this->transaction = $transaction;
			$this->log_path = $log_path;
			$this->tracking_info = $this->callApi(
						AGSHOST."/payment/mobilev2/transaction/tracking.jsp",
						array(
							"storeID"=>$this->store_id,
							"trackingID"=>$this->tracking_id
						)

			);

			$this->log($this->tracking_info);
			$this->tracking_info = json_decode($this->tracking_info,true);
		}

		function setLogging($b) {
			$this->logging = $b;
		}

		function log($str) {
			if($this->logging){
    			$path = $this->log_path;
                $folder_path = "";

                if($path == null){
                    $path = "/log";
                }

    			$folder = dirname(__FILE__).$path;
    			if (!@file_exists($folder)) {
    				@mkdir($folder);
    			}

    			if (!$this->logfile ) {
    				$this->logfile = @fopen($folder."/".date("Y-m-d").".log","a");
    				if (!$this->logfile) {
    					die($folder."/".date("Y-m-d").".log ������ ������ �� �����ϴ�");
    				}
    			}

    			if ($this->logfile && $this->logging) {
    				$str = date("Y-m-d H:i:s")."==>".$str."\n";
    				@fwrite($this->logfile,$str);
    			}
			}else {
				if ($this->logfile) {
					@fclose($this->logfile);
				}
			}
		}

		function getTrackingInfo() {
			return $this->tracking_info;
		}

		function callApi($url , $params) {

			$query = "";
			foreach($params as $key => $value) {
				$query .= $key."=".$value;
				$query .= "&";
			}
			$nurl = $url . "?" . $query;
            $this->log($nurl);

			if(function_exists('curl_version')) {

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $nurl);
				curl_setopt($ch, CURLOPT_TIMEOUT, 90);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90 );
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_USERAGENT, 'AGSMobile 2.0');
				$str = curl_exec($ch);
				curl_close($ch);
			}else {
				$str = file_get_contents($nurl);
			}


			return $str;
		}




		function approve() {

			$ret = array(
				"status"=>"error",
				"message"=>"�� �� ���� ����"
			);

            $data = array(

            );



			switch($this->transaction) {

                /* virtual */
				case "virtual" : {
					$ret["paytype"]= "virtual";

                    $html = $this->callApi(
                        AGSHOST."/payment/mobilev2/transaction/virtual.jsp",
                        array(
                            "storeID"=>$this->store_id,
                            "trackingID"=>$this->tracking_id,
                            "type"=>"approve"
                        )
                    );
                    $this->log($html);
                    if ($html) {
                        $json = json_decode($html,true);

                       	if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
                       	if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

	                    if ($json['Success'] != "y") {
	                            $ret["status"]="error";
	                            $ret["message"]=$json['ResMsg'];
	                            $ret["data"]=null;
                        }else {
                            $ret["status"]="ok";
                            $ret["message"]="ok";


                            $ret["data"]=array(

                            	//�Ʒ��� �� ���� ����..
                            	"AuthTy" => $json['AuthTy'],
                                "SubTy" => $json['SubTy'],
                                "NetCancelId" => $json['NetCancelId'],
                                "StoreId" => $json['StoreId'],
                                "OrdNo" => $json['OrdNo'],
                                "Amt" => $json['Amt'],
                                "EscrowYn"=> $json['EscrowYn'],
                                "NoInt" => $json['DeviId'] == "9000400002" ? "y" : "n",
                                "EscrowSendNo" => $json['EscrowSendNo'],

								"VirtualNo" => $json['VirtualNum'],         // �Աݰ��¹�ȣ(�������¹�ȣ)
                                "BankCode" => $json['BankCode'],            // �Ա������ڵ�
                                "SuccessTime" => $json['SuccessTime'],           // ��������
                                "DueDate" => $json['DueDate']          // ��������

                            );
                        }
	              }



				};break;



                /* hp */
				case "hp" : {
					$ret["paytype"]= "hp";
                    $html = $this->callApi(
                        AGSHOST."/payment/mobilev2/transaction/phone.jsp",
                        array(
                            "storeID"=>$this->store_id,
                            "trackingID"=>$this->tracking_id,
                            "type"=>"approve"
                        )
                    );
                    $this->log($html);


                    if ($html) {
                        $json = json_decode($html,true);
                        if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
                        if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

                        if ($json['Success'] != "y") {
                            $ret["status"]="error";
                            $ret["message"]=$json['ResMsg'];
                            $ret["data"]=null;
                        }else {
                            $ret["status"]="ok";
                            $ret["message"]="ok";
                            $this->netCancelId = $json['NetCancelId'];
                            $ret["data"]=array(

                            	"AuthTy" => $json['AuthTy'],
                                "SubTy" => $json['SubTy'],
                                "NetCancelId" => $json['NetCancelId'],
                                "StoreId" => $json['StoreId'],
                                "OrdNo" => $json['OrdNo'],
                                "Amt" => $json['Amt'],
                                "EscrowYn"=> $json['EscrowYn'],
                                "NoInt" => $json['DeviId'] == "9000400002" ? "y" : "n",


                                 "AdmTID" => $json['HpTid'],           // ����TID
                                "PhoneCompany" => $json['HpCompany'],      // ���Ż�
                                "Phone" => $json['HpNumber']               // �ڵ��� ��ȣ
                            );
                        }

                    }


				};break;



                /* kmpi */
				case "kmpi" :
				case "ansim" :
				case "xansim" :
				{
					$ret["paytype"]= "card";

                    $html = $this->callApi(
                        AGSHOST."/payment/mobilev2/transaction/ansim.jsp",
                        array(
                            "storeID"=>$this->store_id,
                            "trackingID"=>$this->tracking_id,
                            "type"=>"approve"
                        )
                    );
                    $this->log($html);
                    if ($html) {
                        $json = json_decode($html,true);
                        if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
                        if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

                        if ($json['Success'] != "y") {
                            $ret["status"]="error";
                            $ret["message"]=$json['FailReason'];
                            $ret["data"]=null;
                        }else {
                            $ret["status"]="ok";
                            $ret["message"]="ok";
             				$this->netCancelId = $json['NetCancelId'];
                            $ret["data"]=array(


                            	"AuthTy" => $json['AuthTy'],
                                "SubTy" => $json['SubTy'],

                                "NetCancelId" => $json['NetCancelId'],
                                "StoreId" => $json['StoreId'],
                                "OrdNo" => $json['OrdNo'],
                                "Amt" => $json['Amt'],
                                "EscrowYn"=> $json['EscrowYn'],
                                "NoInt" => $json['DeviId'] == "9000400002" ? "y" : "n",
                                "EscrowSendNo" => $json['EscrowSendNo'],

                                "BusiCd" => $json['Code'],     // �����ڵ�
                                "AdmNo" => $json['AdmNo'],     // ���ι�ȣ
                                "AdmTime" => $json['AdmTime'], // ���νð�
                                "CardCd" => $json['CardType'], // ī�����ڵ�
                                "CardNm" => $json['CardName'], // ī������
                                "DealNo" => $json['SendNo'],    // �ŷ���ȣ
                                "PartialMm" => $json["CardPartialMm"]
                            );
                        }

                    }

				};break;





                /* isp */
				case "isp" : {

					$ret["paytype"]= "card";


					$html = $this->callApi(
						AGSHOST."/payment/mobilev2/transaction/isp.jsp",
						array(
							"storeID"=>$this->store_id,
							"trackingID"=>$this->tracking_id,
							"type"=>"approve"
						)
					);
					$this->log($html);

					if ($html) {
						$json = json_decode($html,true);
						if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
						if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

						if ($json['Success'] != "y") {
							$ret["status"]="error";
							$ret["message"]=$json['FailReason'];
							$ret["data"]=null;
						}else {
							$ret["status"]="ok";
							$ret["message"]="ok";
							  /*
								 �������� �°� json���� �̾ƿͼ� data�� �����ؾ���..
								 �� �� ��� data�� 12���� ���ڸ� ������
								 ������ �ʿ��� �������� tracking_info ���� ��������.
                             */
                            $this->netCancelId = $json['NetCancelId'];
							$ret["data"]=array(
								//�Ʒ��� �� ���� ����..
								"AuthTy" => $json['AuthTy'],
                                "SubTy" => $json['SubTy'],

                                "NetCancelId" => $json['NetCancelId'],
                                "StoreId" => $json['StoreId'],
                                "OrdNo" => $json['OrdNo'],
                                "Amt" => $json['Amt'],
                                "EscrowYn"=> $json['EscrowYn'],
                                "NoInt" => $json['DeviId'] == "9000400002" ? "y" : "n",
                                "EscrowSendNo" => $json['EscrowSendNo'],

                                "BusiCd" => $json['Code'],     // �����ڵ�
                                "AdmNo" => $json['AdmNo'],     // ���ι�ȣ
                                "AdmTime" => $json['AdmTime'], // ���νð�
                                "CardCd" => $json['CardType'], // ī�����ڵ�
                                "CardNm" => $json['CardName'], // ī������
                                "DealNo" => $json['SendNo'],    // �ŷ���ȣ
                                "PartialMm" => $json["CardPartialMm"]
							);
						}

					}



				}break;



                /* type default */
				default : {
					$this->log("���� Ÿ���� �� �� �Ǿ����ϴ�." . $this->transaction);
					$ret["message"] = "���� Ÿ�� ����";
					$ret["status"] = "error";
				}break;
			}

			return $ret;

		}

		function forceCancel() {
			return $this->cancel("","","",$this->netCancelId);
		}

        function cancel($AdmNo, $AdmDt, $SendNo, $NetCancID = "") {

            $ret = array(
                "status"=>"error",
                 "message"=>"�� �� ���� ����"
            );

            $data = array(

            );



            switch($this->transaction) {

                /* ansim, xansim, kmpi */
                case "ansim" :
                case "xansim" :
                case "kmpi" :
                {
                    $ret["paytype"]= "card";

                    $html = $this->callApi(
                        AGSHOST."/payment/mobilev2/transaction/ansim.jsp",
                        array(
                            "storeID"=>$this->store_id,
                            "trackingID"=>$this->tracking_id,
                            "admNo"=>$AdmNo,
                            "sendNo"=>$SendNo,
                            "admDt"=>$AdmDt,
                            "NetCancelId"=>$NetCancID,
                            "type"=>"cancel"
                        )
                    );
                    $this->log($html);

                    if ($html) {
                        $json = json_decode($html,true);
                        if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
                        if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

                        if ($json['Success'] != "y") {
                            $ret["status"]="error";
                            $ret["message"]=$json['FailReason'];
                            $ret["data"]=null;
                        }else {
                            $ret["status"]="ok";
                            $ret["message"]="ok";

                            $ret["data"]=array(
                               "StoreId" => $json['StoreId'],  // ��üID
                                "AdmNo" => $json['AdmNo'],     // ���ι�ȣ
                                "AdmTime" => $json['DealTime'], // ���νð�
                                "Code" => $json['Code']        // S000 : ����,  S001 : ��ó��(�̹�ó���Ȱ�),  E999 : ��Ÿ����.
                           );
                        }

                    }

                };break;

                /* isp */
                case "isp" : {

                    $ret["paytype"]= "card";

                    $html = $this->callApi(
                        AGSHOST."/payment/mobilev2/transaction/isp.jsp",
                        array(
                            "storeID"=>$this->store_id,
                            "trackingID"=>$this->tracking_id,
                            "admNo"=>$AdmNo,
                            "sendNo"=>$SendNo,
                            "admDt"=>$AdmDt,
                            "NetCancelId"=>$NetCancID,
                            "type"=>"cancel"
                        )
                    );
                    $this->log($html);

                    if ($html) {
                        $json = json_decode($html,true);
                        if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
                        if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

                        if ($json['Success'] != "y") {
                            $ret["status"]="error";
                            $ret["message"]=$json['FailReason'];
                            $ret["data"]=null;
                        }else {
                            $ret["status"]="ok";
                            $ret["message"]="ok";

                            $ret["data"]=array(
                            	"AuthTy" => $json['AuthTy'],
                                "SubTy" => $json['SubTy'],
                                 "StoreId" => $json['StoreId'],  // ��üID
                                "AdmNo" => $json['AdmNo'],     // ���ι�ȣ
                                "AdmTime" => $json['DealTime'], // ���νð�
                                "Code" => $json['Code']        // S000 : ����,  S001 : ��ó��(�̹�ó���Ȱ�),  E999 : ��Ÿ����.

                            );
                        }

                    }



                }break;



                /* hp */
                case "hp" : {

                    $ret["paytype"]= "hp";

                    $html = $this->callApi(
                        AGSHOST."/payment/mobilev2/transaction/phone.jsp",
                        array(
                            "storeID"=>$this->store_id,
                            "trackingID"=>$this->tracking_id,
                            "NetCancelId"=>$NetCancID,
                            "type"=>"cancel"
                        )
                    );
                    $this->log($html);

                    if ($html) {
                        $json = json_decode($html,true);
                        if (!is_array($json) || !isset($json['code'])) {
                       		return $ret;
                       	}
                        if ($json['code'] == 400) {
                       		$ret["message"] = $json['message'];
	                        $ret["status"] = "error";
	                        return $ret;
                       	}

                       	$json = $json['data'];

                        if ($json['Success'] != "y") {
                            $ret["status"]="error";
                            $ret["message"]=$json['ResMsg'];
                            $ret["data"]=null;
                        }else {
                            $ret["status"]="ok";
                            $ret["message"]="ok";

                            $ret["data"]=array(
                            	"AuthTy" => $json['AuthTy'],
                                "SubTy" => $json['SubTy'],
                                 "StoreId" => $json['StoreId'],  // ��üID
                                "AdmTime" => $json['AdmTime'], // ���νð�
                               	"AdmTID" => $json['HpTid']           // ����TID
                            );
                        }

                    }



                }break;



                /* type default */
                default : {
                    $this->log("���� Ÿ���� �� �� �Ǿ����ϴ�.");
                    $ret["message"] = "���� Ÿ�� ����";
                    $ret["status"] = "error";
                }break;
            }

            return $ret;

        }

	}
?>
