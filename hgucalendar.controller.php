<?php
/**
 * @class  hgucalendarController
 * @author CRA (developers@developers.com)
 * Controller class of memo module
 **/

	class hgucalendarController extends hgucalendar {
	 /**
         * @brief 초기화
         **/
        function init() {
        }
        /**
         * @brief BOOK 입력
         **/
        function procHgucalendarUserReg() {
            // request 값을 모두 받음
            $obj = Context::getRequestVars();
 
			$obj1 = Context::get('logged_info');

			// 로그인한사람 아이디 가져오기
			$obj->id = $obj1->user_id;

            // 단체명 확인
            $obj->module_srl = Context::get('groupname');
			$obj->regdate = date("Y-m-d H:i:s");

			 // module_srl이 있으면 update
			$output = executeQuery("hgucalendar.UserRegCheck", $obj);
			if($output->data != null)
			{
				echo('<script>alert("이미 등록된 단체가 있습니다");location.href="./";</script>');
			}
			else{
				executeQuery("hgucalendar.userReg", $obj);
				echo('<script>alert("등록 되었습니다.");location.href="./'.$this->mid.'";</script>');
			$this->setMessage('success_updated');
			} 
        }

		function procHgucalendarEventReg()	{
			$obj = Context::getRequestVars();
			$arr = array( 
				1=>array($obj->time1_1, $obj->shour1_1, $obj->sminute1_1, "stime1_1"),
				2=>array($obj->time1_2, $obj->shour1_2, $obj->sminute1_2, 
				"stime1_2"),
				3=>array($obj->time2_1, $obj->shour2_1, $obj->sminute2_1, "stime2_1"),
				4=>array($obj->time2_2, $obj->shour2_2, $obj->sminute2_2, "stime2_2"),
				5=>array($obj->time3_1, $obj->shour3_1, $obj->sminute3_1, "stime3_1"),
				6=>array($obj->time3_2, $obj->shour3_2, $obj->sminute3_2, "stime3_2"),
				7=>array($obj->time4_1, $obj->shour4_1, $obj->sminute4_1, "stime4_1"),
				8=>array($obj->time4_2, $obj->shour4_2, $obj->sminute4_2, "stime4_2"),
				9=>array($obj->time5_1, $obj->shour5_1, $obj->sminute5_1, "stime5_1"),
				10=>array($obj->time5_2, $obj->shour5_2, $obj->sminute5_2, "stime5_2"));
			for($i=1; $i<11; $i++){
				if('' != $arr[$i][0]){
					if("오후" == $arr[$i][0]){
						if(12>$arr[$i][1]){ // 시간
							if($arr[$i][2]<10) // 분
								$timesum = ($arr[$i][1]+12).":0".$arr[$i][2];
							else
								$timesum = ($arr[$i][1]+12).":".$arr[$i][2];
						}
					}
					else{
						if($arr[$i][2]<10)
							$timesum = ($arr[$i][1]+12).":0".$arr[$i][2];
						else
							$timesum = $arr[$i][1].":".$arr[$i][2];
					}
				$obj->$arr[$i][3] = $timesum;
				}
			}
			
			$obj1->title = $obj->eventname;
			$obj1->content = $obj->content;
			$document_srl = getNextSequence(); //document serial 번호 부여
			$obj1->document_srl = $document_srl;
			$obj1->module_srl = $this->module_srl;
			$obj1->allow_comment = 'Y';
			$obj1->allow_trackback = 'Y';

			$oDocumentController = &getController('document');
			$output = $oDocumentController->insertDocument($obj1);
			$obj->document_srl = $document_srl; //문서 번호 Context등록

			//작성 날짜 Context에 추가
			$obj->regdate = date("Y-m-d H:i:s");

			$output = executeQuery("hgucalendar.eventReg", $obj);

			echo('<script>alert("등록되었습니다.");location.href="./'.$this->mid.'";</script>');

		}
		
		function procHgucalendarGoupPermit() {
			$obj = Context::getRequestVars();
			
			if($obj->command == 1){
				$output = executeQuery("hgucalendar.groupPermit", $obj);
			}
			else if($obj->command == 2){
				$output = executeQuery("hgucalendar.groupCancel", $obj);
			}
			else
				echo('<script>alert("치명적 오류")</script>');

			if($output->message == 'success'){
				echo('<script>alert("성공하였습니다.");history.go(-1);</script>');
			}
			else
				echo('<script>alert("치명적 오류가 발생했습니다.")</script>');
		}

		/*
		function procHgucalendarEventInfo() {
			$myData->document_srl = Context::get('document_srl');
			// 데이터를 처리합니다.
			//debugPrint($myData);

			$obj = Context::getRequestVars();
			$document_srl = $obj->document_srl;
			$oDocument->document_srl = $document_srl;

			$ohgucalendarModel = &getModel('hgucalendar');
			$output = $ohgucalendarModel->getHgucalendarEvent($obj);

			$this->add('eventinfo', $output->data[0]);
			//$eventinfo = Context::get('eventinfo');
		}
		*/ // 모달팝업 뷰. 추후 구현 예정.

		
	}
?>
