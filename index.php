<?php 
include_once 'ApiCaller.php';
 $accessToken = '2AAABLKmtbUDdItTitaF2AjzXeS-ATMm9wSl7biaWaRzOxaGjwHRftneN_aIpiexgeM_GZkj4hss*';
 $applicationId = 'CBJCHBCAABAAJK-RN0XXhnhgjHFLdtTCBFxD6zp81l_B';
 $gateway = ' https://api.na1.echosign.com/api/rest/v5';
 $host = 'api.na1.echosign.com';
 $contentType = 'Content-Type: application/json';

$echosign = '{   
    "documentCreationInfo":{      
         
        "name":"MyTestAgreement",  "recipientSetInfos":[{
                "recipientSetMemberInfos":[{                  
                    "email":"britta.alex@kaplan.com",
                    ],            
                "recipientSetRole":"SIGNER"}],      
                "signatureType":"ESIGN",      
                "signatureFlow":"SENDER_SIGNATURE_NOT_REQUIRED"   
    }
}';
 /**
     * @param FileHelper $fileHelper
     * @throws CException
     * @return string|mixed
     */
$file = 'testechosign';
send($file);
 function send($fileHelper)
	{
		$getDocArr = uploadDocument($fileHelper); // transientDocumentId
		$jsonCount = 0;
        
        $docID = json_decode($getDocArr); // convert json to object
     
     echo   $transientDocumentId = $docID->transientDocumentId;exit;

		/** Generate the password */
		$rpassword = randomPassword();

        overwriteFileinfos($transientDocumentId,$rpassword);
        $echosign = array(
            "documentCreationInfo" => array(
                "fileInfos" => array("transientDocumentId"=>$transientDocumentId),
                "name" => "test",
                "recipientSetInfos"=>array(
                    "recipientSetMemberInfos"=>array( "email"=>"britta.alex@kaplan.com",
                        "RecipientSecurityOption" => array("authenticationMethod"=>"PASSWORD","password"=>"123456")),
                        array("email"=>"ryan.quek@kaplan.com",
                         "RecipientSecurityOption" => array("authenticationMethod"=>"PASSWORD","password"=>"45678"),
                        ),
                    "recipientSetRole" => "SIGNER"
                  
                ),
                "recipientSetRole" => "SIGNER",
                "signatureType"=>"ESIGN",
                "signatureFlow"=>"SENDER_SIGNATURE_NOT_REQUIRED" 
            )
            
        );
     echo json_encode($echosign);exit;
        $agreementCall = postCall('agreements',json_encode($echosign),array('Content-Type: application/json'));

		/** add document(s) & send contract */
		$agreement = json_decode($agreementCall);echo "<pre>";print_r($agreement);exit;
		return $agreement->agreementId;
		//return $this->createNewAgreement($agreementId, $rpassword);
	}

    /**
     * @param string $agreementId
     * @return string|null
     */
	 function getSignUrl($agreementId)
	{

        $returnVal = getCall('agreements/'.$agreementId.'/signingUrls',array(),array('Content-Type: application/json'));
        $returnArray = json_decode($returnVal,true);
        if(isset($returnArray['signingUrls'][0]['esignUrl']))
            return $returnArray['signingUrls'][0]['esignUrl'];
        else
            return null;
	}
 function getAuditTrail($agreementId)
	{
		/** get access token
		*/
		$token = json_decode(getToken());
		$accessToken = $token; // accessToken & expiresIn
		$url = $gateway.'agreements/'.$agreementId.'/auditTrail';
		return methods($url,'',array('Access-Token:'.$accessToken,'Content-Type: application/json'),'GET');
	}
	
	 function getAllAgreement()
	{
		/** get access token
		*/
		$token = json_decode($this->getToken());
		$accessToken = $accessToken; // accessToken & expiresIn
		$url = $gateway.'agreements';
		return methods($url,'',array('Access-Token:'.$accessToken,'Content-Type: application/json'),'GET');
	}

	 function getDataEnteredByUser($agreementId)
	{
		return getCall('agreements/'.$agreementId.'/formData',array(),array('Content-Type: application/json'));
	}
	
	 function getDocument($agreementId)
	{
        return getCall('agreements/'.$agreementId.'/combinedDocument',array(),array('Content-Type: application/json'));
	}
	
	 function getContractStatus($agreementId) {
        return $getCall('agreements/'.$agreementId,array(),array('Content-Type: application/json'));
	}

	 function retrieveStatus($accessToken,$agreementId)
	{
		$url = $gateway.'agreements/'.$agreementId;
		return methods($url,'',array('Access-Token: '.$accessToken,'Content-Type: application/json'),'GET');
	}
	
	 function overwriteFileinfos($transientDocumentId,$rpassword)
	{
		
                $jsonObject = '"documentCreationInfo":{      
        "fileInfos":[{
            "transientDocumentId":'.$transientDocumentId         
         .'}]}';
		
        
		return $options = json_encode($jsonObject);
	}

    /**
     * @param string $accessToken
     * @param string $transientDocumentId
     * @param string $rpassword
     * @return string
     * @deprecated Don't use this method anymore.
     */
	
	 function sendContract($accessToken,$transientDocumentId,$rpassword)
	{
		$url = $gateway.'agreements';
		
		$this->overwriteFileinfos($transientDocumentId,$rpassword);
		
		return $this->methods($url,$options,array('Access-Token: '.$accessToken,'Content-Type: application/json'),'POST');
	}
	
	 function randomPassword() {
		//$alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
		$alphabet = "415629036691439409091118333421";
		$pass = array();
		$alphaLength = strlen($alphabet) - 1;
		for ($i = 0; $i < 6; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass);
	}


   

    /**
     * @param FileHelper $fileHelper
     * @return mixed|string
     */

	 function uploadDocument($file)
	{
        $postField =
            array
            (
                'File-Name'=>$file,
                'Mime-Type'=>'application/pdf',
                'File'=>'@'."./".$file,
            );//echo "<pre>"; print_r($postField);exit;
		return postCall('transientDocuments',$postField);
	}
    
     function setTokenInHeader($header=array())
    { 
        $_found = false;
        foreach ($header as $_header) {
            if (strpos($_header, 'Access-Token: ') !== false) {
                $_found = true;
                break;
            }
        }
        if (!$_found) {

            $header[] = 'Access-Token: ' .'3AAABLblqZhAiBEJRCc3ffDZdv0lUPdT6fmve8cvmw2d0PWS8sMaprYH5PxBEgiv_yIJoFkhYMlX0aM6e3IU4A2zUXcxekLpo';
        }
        return $header;
    }

    /**
     * @return mixed|FileHelper
     */
     function getContractFile()
    {
        return $_contractFile;
    }

    /**
     * @param mixed $contractFile
     */
     function setContractFile(FileHelper $contractFile)
    {
        $_contractFile = $contractFile;
    }

     function getCall($api,$postField,$header = array()) {
        return ApiCaller::methods("https://api.echosign.com/api/rest/v5".$api,$postField,setTokenInHeader($header),'GET');
    }

     function postCall($api,$postField,$header = array()) {
        return ApiCaller::methods("https://api.na1.echosign.com/api/rest/v5/".$api,$postField,setTokenInHeader($header),'POST');
    }

     function putCall($api,$postField,$header = array()) {
        return ApiCaller::methods("https://api.echosign.com/api/rest/v5".$api,$postField,setTokenInHeader($header),'PUT');
    }

    /**
     * @return EchoSignToken
     * @throws CHttpException
     */
	 function getToken()
	{
        if($token == null) {
//            $url = $gateway.'auth/tokens';
//
//           $json = array("code" => "CBNCKBAAHBCAABAAuKPQohW6CtCbLmUdX3BHytzFlbFpp3Uk",
//                "client_id" => "CBJCHBCAABAAJK-RN0XXhnhgjHFLdtTCBFxD6zp81l_B",
//                "client_secret" => "I51F48jWC_3DDWvM0wfcAon1y9n_5sSB",
//                "redirect_uri" => "https://test-hechosg.au.cloudhub.io/callback/?route=development",
//                "grant_type" => "authorization_code");
//           
//            $data = json_encode($json);
//
//            $contentLength = strlen($data);
//            $jsonString = ApiCaller::methods($url,$data,array('Content-Type: application/json','Content-Length: ' .strlen($data)),'POST');
//            $token = $jsonString;
            $token = '2AAABLKmtbUDdItTitaF2AjzXeS-ATMm9wSl7biaWaRzOxaGjwHRftneN_aIpiexgeM_GZkj4hss*';
        }
        return $token;
	}
	
	 function methods($url,$data,$header = array(),$method=NULL)
	{
		$ch = curl_init();
		if($method == 'PUT')
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		else if($method == 'POST')
		{
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		else if($method == 'GET')
		{
			curl_setopt($ch, CURLOPT_HTTPGET, 1); 
		}
		else
		{
			throw new CHttpException('- Invalid Request Method.');
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$responseCode = curl_getinfo ($ch,CURLINFO_HTTP_CODE);
		$response_info = curl_getinfo($ch);
		curl_close($ch);
		
		if($responseCode >= 400)
		{
			/*
			echo "<br><br>";
			var_dump($data);
			echo "<br><br>";
		
			echo "<br><br>".print_r($response_info)."<br><br>";
			echo "<br><br>".$responseCode."<br><br>";
			echo "<br><br>".$response."<br><br>";
			die();
			*/
			//throw new CHttpException(' - '.$responseCode.' ('.json_decode($response)->message.')');
			return $response;
			//return $response;
		}
		else
		{
			//echo "<br><br>response : ".$response."<br><br>";
			return $response;
		}
	}
	

 
