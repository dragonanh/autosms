<?php
/**
 * Description of sfWidgetFormViettelTable
 *
 * @author Ico
 */
class sfWidgetFormViettelTable extends sfWidgetForm {

  public function configure($options = array(), $attributes = array()) {
    $this->addRequiredOption('model');
    $this->addRequiredOption('table_method');
    $this->addRequiredOption('relation_type'); //FIELD, ONE2ONE, ONE2MANY
    $this->addRequiredOption('search_method');
    $this->addOption('table_method_input', null);
    $this->addRequiredOption('visible_columns');
    $this->addOption('disable');
    $this->addOption('visible_column_text');
    $this->addOption('list_column_text', null);
    $this->addOption('search_column_text', null);
    $this->addOption('addition_columns', null);
    $this->addOption('addition_columns_value', null);
    $this->addOption('add_empty', false);
    $i18n = sfContext::getInstance()->getI18N();
    $this->addOption('required', false);
    $this->addOption('button_text', $i18n->__("Thêm"));
    $this->addOption('modal_header', $i18n->__("Thêm mới"));
    $this->addOption('modal_text', $i18n->__("Không tìm được kết quả phù hợp với từ khóa"));
    $this->addOption('previous_button_text', $i18n->__("Trước"));
    $this->addOption('next_button_text', $i18n->__("Sau"));
    $this->addOption('close_button_text', $i18n->__("Đóng"));
    $this->addOption('save_button_text', $i18n->__("Lưu"));
    $this->addOption('choice_button_text', $i18n->__("Chọn"));
    $this->addOption('no_result_text', $i18n->__("Chưa có dữ liệu"));
    $this->addOption('form_search', null);//su dung cho truong hop 1 form co nhieu hon 1 nut search
    $this->addOption('orderColumnName', null);
    $this->addOption('orderParamSubmit', null);
    $this->addOption('table_relate_array', null); //truong hop phai lay ra nhieu ca si singer1,singer2, ten bang quan he
    $this->addOption('column_relate_array', null); //truong hop phai lay ra nhieu ca si singer1,singer2, ten cot quan he (Vd alias...)
    $this->addOption('second_param_for_query', null); //truong hop table_method co 2 tham so
    $this->addOption('javascript', null);

    $relateType =  $options["relation_type"];
    $search_method =  $options["search_method"];
	  $model = $options["model"];
    $formSearch = null;
    if(array_key_exists("form_search",$options)){
      $formSearch =  $options["form_search"];
    }
//    var_dump($options['disable']); die();
    $display = "";
    $statusDisable = $this->getOption('disable');
    if($statusDisable){
      $display = 'style="display:none"';
    } else $display = 'style="display:"';


    if($relateType=="ONE2MANY"){
      $formSearchTemplate =  '<input id="vtModalInput_{input.id}" type="text" onblur="trim(this.value)" maxlength="50" onkeypress="return doClick(event,\''. $model .'-btn-search\')" placeholder="{input.keyword}">'
        . '<a href="javascript:ProcessData(' . "'{input.id}','{input.search_method}', 1" . ')" role="button" class="btn" id="'. $model .'-btn-search">{input.search_button_text}</a>';
      if(is_null($formSearch)==false){
        $searchStr = "<input type='checkbox' value='1' id='multiple_search_keys' style='display:none' />";
        foreach ($formSearch as $searchKey => $searchName) {
          $searchStr =$searchStr . "<input onblur='this.value = $.trim(this.value)' type='text' maxlength='50' name='".$searchKey."' id='".$searchKey."' onkeypress=\"return doClick(event,'". $model ."-btn-search')\" placeholder='".$searchName ."'  /> " ;
          $searchStr =$searchStr . "<input onblur='this.value = $.trim(this.value)' type='hidden' name='".$searchKey."_hidden' id='".$searchKey."_hidden' placeholder='".$searchName ."' /> " ;
        }
        $searchStr = $searchStr. '<a href="javascript:ProcessDataCustom(' . "'{input.id}','{input.search_method}', 1" . ')" role="button" class="btn" id="'. $model .'-btn-search">{input.search_button_text}</a>';
        $formSearchTemplate = $searchStr;
      }

      $this->addOption('template.html', '
         <input type="hidden" value="{input.relation_type}" id="vtModal_Relation_Type_{input.id}" />
         <input type="hidden" value="{input.orderColumnName}" id="vtModal_OrderColumnName_{input.id}" />
         <input type="hidden" value="'.$search_method.'" id="vtModal_SearchMethod_{input.id}" />
         <input type="hidden" value="" id="vtModal_Keyword_{input.id}" />
         {input.main_field}
          <table {input.isVtTableShowed} class="table table-condensed table-hover table-bordered table-striped" id="vtTable_{input.id}">
            {input.thead1}
            {input.tbody}
          </table>

          <a '.$display.' href="#vtModal_{input.id}" id="a_vtModal_{input.id}" role="button" class="btn" data-toggle="modal">{input.button_text}</a>

          <div class="modal hide fade" id="vtModal_{input.id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
          style="width: 600px;">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel">{input.modal_header}</h3>
            </div>
            <div class="modal-body">

              <div class="vtModalFormSearch">
                  '.$formSearchTemplate.'
                  <a class="exist-alert" href="javascript:void()" style="color:red;cursor:none;text-decoration:none;"></a>
              </div>

              <image id="vtModal_loading" class="loading" style="display:none" src="/images/loading.gif" title="loading" />
              <div style="display:none;padding-top:10px;" id="vtModalTable_{input.id}" style="padding:10px;">
                <table class="table table-condensed table-hover table-bordered table-striped">
                  {input.thead2}
                  <tbody>
                  </tbody>
                </table>
				<ul class="pager">
				  <li class="Button-Previous"><a href="javascript:void(0)" onclick="return MovePrevious(' . "'{input.id}','{input.search_method}'" . ')">{input.previous_button_text}</a></li>
                  <li class="Button-Paging"></li>
				  <li class="Button-Next"><a href="javascript:void(0)" onclick="return MoveNext(' . "'{input.id}','{input.search_method}'" . ')">{input.next_button_text}</a></li>
				</ul>
				<div class="description-page" style="display:none">Trang: <span>1/10</span></div>
              </div>
              <div style="padding-top:10px;">
                <p id="vtModalText_{input.id}" style="display:none;">{input.modal_text}</p>
              </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">{input.close_button_text}</button>
                <button class="btn btn-primary" data-dismiss="modal" href="javascript:void(0)" onclick="return UpdateData(' . "'{input.id}', '{input.relation_type}'" . ');">{input.save_button_text}</button>
                
            </div>
          </div>
		  {input.modal_current_page}
    ');
    }else if($relateType=="FIELD"){
      $formSearchTemplate =  '<input id="vtModalInput_{input.id}" type="text"  maxlength="50" placeholder="{input.keyword}">'
        . '<a href="javascript:ProcessData(' . "'{input.id}','{input.search_method}', 1" . ')" role="button" class="btn" id="'. $model .'-btn-search">{input.search_button_text}</a>';
      if(is_null($formSearch)==false){
        $searchStr = "<input type='checkbox' value='1' id='multiple_search_keys' style='display:none' />";
        foreach ($formSearch as $searchKey => $searchName) {
          $searchStr =$searchStr . "<input type='text' maxlength='50' name='".$searchKey."' id='".$searchKey."' onkeypress=\"return doClick(event,'". $model ."-btn-search')\" placeholder='".$searchName ."'  /> " ;
          $searchStr =$searchStr . "<input type='hidden' name='".$searchKey."_hidden' id='".$searchKey."_hidden' placeholder='".$searchName ."' /> " ;
        }
        $searchStr = $searchStr. '<a href="javascript:ProcessDataCustom(' . "'{input.id}','{input.search_method}', 1" . ')" role="button" class="btn" id="'. $model .'-btn-search">{input.search_button_text}</a>';
        $formSearchTemplate = $searchStr;
      }

      $this->addOption('template.html', '
         <input type="hidden" value="{input.relation_type}" id="vtModal_Relation_Type_{input.id}" />
         <input type="hidden" value="'.$search_method.'" id="vtModal_SearchMethod_{input.id}" />
         <input type="hidden" value="" id="vtModal_Keyword_{input.id}" />
         {input.main_field}
          <table {input.isVtTableShowed} class="table table-condensed table-hover table-bordered table-striped" id="vtTable_{input.id}">
            {input.thead1}
            {input.tbody}
          </table>
          <a href="#vtModal_{input.id}" role="button" class="btn" data-toggle="modal">{input.button_text}</a>
          <div class="modal hide fade" id="vtModal_{input.id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
          style="width:600px;"      >
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel">{input.modal_header}</h3>
            </div>
            <div class="modal-body">
             <div class="vtModalFormSearch">
                  '.$formSearchTemplate.'
              </div>
              <image id="vtModal_loading" class="loading" style="display:none" src="/images/loading.gif" title="loading" />
              <div style="display:none;padding-top:10px;" id="vtModalTable_{input.id}" style="padding:10px;">
                <table class="table table-condensed table-hover table-bordered table-striped">
                  {input.thead2}
                  <tbody>
                  </tbody>
                </table>
                <ul class="pager">
                  <li class="Button-Previous"><a href="javascript:void(0)" onclick="return MovePrevious(' . "'{input.id}','{input.search_method}'" . ')">{input.previous_button_text}</a></li>
                  <li class="Button-Paging"></li>
                  <li class="Button-Next"><a href="javascript:void(0)" onclick="return MoveNext(' . "'{input.id}','{input.search_method}'" . ')">{input.next_button_text}</a></li>
                </ul>
                <div class="description-page" style="display:none">Trang: <span>1/10</span></div>
              </div>
              <div style="padding-top:10px;">
                <p id="vtModalText_{input.id}" style="display:none;">{input.modal_text}</p>
              </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">{input.close_button_text}</button>
                <button class="btn btn-primary" data-dismiss="modal" href="javascript:void(0)" onclick="return UpdateData(' . "'{input.id}', '{input.relation_type}'" . ');">{input.save_button_text}</button>
            </div>
          </div>


		  {input.modal_current_page}
    ');
    }
    else if($relateType=="ONE2ONE"){
	    $player = "<div class='player' style='height: 20px'><div id='JWPlayer'></div></div>";
      $formSearchTemplate =  '<input id="vtModalInput_{input.id}" type="text" maxlength="50" placeholder="{input.keyword}">'
        . '<a href="javascript:ProcessData(' . "'{input.id}','{input.search_method}', 1" . ')" role="button" class="btn" id="'. $model .'-btn-search">{input.search_button_text}</a>';
      if(is_null($formSearch)==false){
        $searchStr = "<input type='checkbox' value='1' id='multiple_search_keys' style='display:none' />";
        foreach ($formSearch as $searchKey => $searchName) {
          $searchStr =$searchStr . "<input type='text' maxlength='50' name='".$searchKey."' id='".$searchKey."' onkeypress=\"return doClick(event,'". $model ."-btn-search')\" placeholder='".$searchName ."' /> " ;
          $searchStr =$searchStr . "<input type='hidden' name='".$searchKey."_hidden' id='".$searchKey."_hidden' placeholder='".$searchName ."' /> " ;
        }
        $searchStr = $searchStr. '<a href="javascript:ProcessDataCustom(' . "'{input.id}','{input.search_method}', 1" . ')" role="button" class="btn" id="'. $model .'-btn-search">{input.search_button_text}</a>';
        $formSearchTemplate = $searchStr;
      }
      $this->addOption('template.html', '
          <input type="hidden" value="{input.relation_type}" id="vtModal_Relation_Type_{input.id}" />
          <input type="hidden" value="{button.choice_button_text}" id="vtModal_choice_button_{input.id}" />
          <input type="hidden" value="'.$search_method.'" id="vtModal_SearchMethod_{input.id}" />
          <input type="hidden" value="" id="vtModal_Keyword_{input.id}" />
          {input.main_field}
          <table {input.isVtTableShowed} class="table table-condensed table-hover table-bordered table-striped" id="vtTable_{input.id}">
            {input.theadPage}
            {input.tbodyPage}
          </table>
          <a href="#vtModal_{input.id}" role="button" class="btn" data-toggle="modal">{input.button_text}</a>

          <div class="modal hide fade" id="vtModal_{input.id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
            style="width:600px;" >
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="myModalLabel">{input.modal_header}</h3>
            </div>
            <div class="modal-body">
              <div class="vtModalFormSearch">
                  '.$formSearchTemplate.'
              </div>
              <image id="vtModal_loading" class="loading" style="display:none" src="/images/loading.gif" title="loading" />
              <div style="display:none;padding-top:10px;" id="vtModalTable_{input.id}" style="padding:10px;">
              '.$player.'
                <table class="table table-condensed table-hover table-bordered table-striped">
                  {input.theadPopup}
                  <tbody>
                  </tbody>
                </table>
				       <ul class="pager">
				         <li class="Button-Previous"><a href="javascript:void(0)" onclick="return MovePrevious(' . "'{input.id}','{input.search_method}'" . ')">{input.previous_button_text}</a></li>
                  <li class="Button-Paging"></li>
				         <li class="Button-Next"><a href="javascript:void(0)" onclick="return MoveNext(' . "'{input.id}','{input.search_method}'" . ')">{input.next_button_text}</a></li>
				       </ul>
				       <div class="description-page" style="display:none">Trang: <span>1/10</span></div>
              </div>
              <div style="padding-top:10px;">
                <p id="vtModalText_{input.id}" style="display:none;">{input.modal_text}</p>
              </div>
            </div>
          </div>
		  {input.modal_current_page}
    ');
    }


    $this->addOption('template.javascript', '');
  }

  public function getJavascripts() {

    $jsArray = $this->getOption('javascript');
    if($jsArray){
      return $jsArray;
    }
    return array(
        '/js/sfWidgetFormViettelTable.js',
        '/js/vtCommon.js'
    );
  }

  public function render($name, $value = null, $attributes = array(), $errors = array()) {
    $i18n = sfContext::getInstance()->getI18N();
    $disable = '';
    if($this->getOption('disable')){
      $disable = 'disabled="true"';
    }

    $relationType = $this->getOption('relation_type');
    $model = $this->getOption('model');

    $visibleColumns = $this->getOption('visible_columns');

    $orderColumnName = "";
    if(is_null($this->getOption("orderColumnName"))==false){
      $orderColumnName = $this->getOption("orderColumnName");
    }


    $orderParamSubmit =  sfContext::getInstance()->getRequest()->getParameter("vt_album_vt_song_list_ordernumber",null);

    if($relationType=="ONE2MANY" || $relationType=="FIELD"){

      $thead = '<thead>
                  <tr>
                    <th>
                      <input  '. $disable.' type="checkbox" checked="checked" class="vtTableCheckbox_{input.id}_main" onchange="ToggleAllByClassName({jsinput})" value="">
                    </th>';
      $thead = str_replace("{jsinput}", "'vtTableCheckbox_{input.id}'", $thead);
      $thead = str_replace("{input.id}", $this->generateId($name), $thead);

      $listColumnText = $this->getOption('list_column_text'); //visible_column_text
      $searchColumnText = $this->getOption('search_column_text'); //visible_column_text
      $addColumns = $this->getOption('addition_columns'); //visible_column_text
      $addColumnsValue = $this->getOption('addition_columns_value'); //visible_column_text


      $thead1 =  $thead;
      $thead2 =  $thead;
      foreach ($listColumnText as $column) {
          $thead1 = $thead1 . '<th>' . $column . '</th>';
      }

      //khanhnq16
      if($addColumns != null){
        foreach ($addColumns as $column) {
          $thead1 = $thead1 . '<th>' . $column . '</th>';
        }

      }
      foreach ($searchColumnText as $column) {
          $thead2 = $thead2 . '<th>' . $column . '</th>';

      }
      //doi voi popup cho phep sua ordernumber thi them vao day
      if($orderColumnName!=""){
        $thead1 = $thead1 . '<th>Thứ tự</th>';
      }

      $thead1 = $thead1 . '</tr></thead>';
      $thead2 = $thead2 . '</tr></thead>';


      $thead2 = str_replace("ToggleAllByClassName", "ModalToggleAllByClassName", $thead2);
      $thead2 = str_replace("vtTableCheckbox", "vtModalTableCheckbox", $thead2);

      $tbody = '<tbody>';

      $dataQueryList = null;
      $dataQueryList = self::getRecords($value);
//      var_dump($dataQueryList);die;
//      if(count($dataQueryList)>0){}

      $records[] = array();
      foreach ($dataQueryList as $record) {
        $rowId =  $record["id"];
        $tr = '<tr class="info">
                <td>
                  <input  '. $disable.' type="checkbox" id="{jsid}"  checked="checked" class="vtTableCheckbox_{input.id}" onchange="ToggleByClassName({jsinput})" value="'.$rowId.'">
                </td>';
        $tr = str_replace("{jsid}", "{input.id}_" . $record["id"], $tr);
        $tr = str_replace("{jsinput}", "'{input.id}_" . $record["id"] . "'", $tr);
        $tr = str_replace("{input.id}", $this->generateId($name), $tr);


        foreach ($visibleColumns as $column) {
          if($model == "VtCondition" && $column == "type" ){
            $tr = $tr . '<td><span title="'.VtConditionTable::getTextType($record[$column]).'">' .VtConditionTable::getTextType($record[$column]). '</span></td>';
          } else if($model == "VtCondition" && $column == "status" ){
            $tr = $tr . '<td><span title="'.VtConditionTable::getTextStatus($record[$column]).'">' .VtConditionTable::getTextStatus($record[$column]). '</span></td>';
          } else {

          if($column == "showType"){ //for campaign
            $tr = $tr . '<td><span title="'.VtCampaignAvatarTable::getShowType($record[$column]).'">' . VtCampaignAvatarTable::getShowType($record[$column]) . '</span></td>';
          } else {
            $tr = $tr . '<td><span title="'.VtHelper::encodeOutput($record[$column],true).'">' . VtHelper::getLimitStringWithoutEncode($record[$column], 50) . '</span></td>';
          }
          }
        }
        //for VtCampaign
        if($addColumnsValue!= null){
          foreach($addColumnsValue as $addvalue){
            $addvalue =  str_replace("{value}",$record["camAvaId"], $addvalue);
            $tr = $tr.$addvalue;

          }
        }

        if($orderColumnName!=""){
          if(is_null($orderParamSubmit)==false){
//            $tr = $tr . '<td>'.$orderParamSubmit[$rowId].'</td>';
            $tr = $tr . '<td><input class="checkValidateOrderNumber" style="width:30px" maxlength="2" type="text" value="' . $orderParamSubmit[$rowId] . '" id="'.$this->generateId($name).'_ordernumber[]" name="'.$this->generateId($name).'_ordernumber['.$rowId.']" /><span style="display:none; color:red;"> Phải là số từ 1->99</span></td>';
          }else{
            $tr = $tr . '<td><input class="checkValidateOrderNumber" style="width:30px" maxlength="2" type="text" value="' . $record[$orderColumnName] . '" id="'.$this->generateId($name).'_ordernumber[]" name="'.$this->generateId($name).'_ordernumber['.$rowId.']" /><span  style="display:none; color:red;"> Phải là số từ 1->99</span></td>';
          }
        }

        $tr = $tr . '</tr>';
        $tbody = $tbody . $tr;

        $records[$record["id"]] = $record["id"];
      }
      $tbody = $tbody . '</tbody>';


      if ($value == null || count($dataQueryList)==0)
        $isVtTableShowed = 'style="display:none;"';
      else
        $isVtTableShowed = '';





      $template_vars = array(
        '{input.isVtTableShowed}' => $isVtTableShowed,
        '{input.thead1}' => $thead1,
        '{input.thead2}' => $thead2,
        '{input.tbody}' => $tbody,
        '{input.id}' => $this->generateId($name),
        '{input.button_text}' => $this->getOption('button_text'),
        '{input.modal_header}' => $this->getOption('modal_header'),
        '{input.modal_text}' => $this->getOption('modal_text'),
        '{input.keyword}' => $i18n->__("Từ khóa"),
        '{input.search_button_text}' => $i18n->__("Tìm kiếm"),
        '{input.search_method}' => $this->getOption('search_method'),
        '{input.relation_type}' => $this->getOption('relation_type'),
        '{input.previous_button_text}' => $this->getOption('previous_button_text'),
        '{input.next_button_text}' => $this->getOption('next_button_text'),
        '{input.close_button_text}' => $this->getOption('close_button_text'),
        '{input.save_button_text}' => $this->getOption('save_button_text'),
      );


      $hidden_main = null;
      if($relationType=="FIELD"){
//        $hidden_main = new sfWidgetFormInput();
        $hidden_main = new sfWidgetFormInputHidden();
        $template_vars['{input.main_field}'] = $hidden_main->render($name, $value);
      }else{
        $hidden_main = new sfWidgetFormChoice(array(
          'multiple' => true,
          'choices' => $records,
        ));
        $hidden_main->setAttributes(array('style' => 'display:none;'));
        $template_vars['{input.main_field}'] = $hidden_main->render($name, $value);


        if(is_null($this->getOption("orderColumnName"))==false){
            $template_vars['{input.orderColumnName}']  = $this->getOption("orderColumnName");
        }else{
            $template_vars['{input.orderColumnName}'] = "";
        }


      }


    }
    else if($relationType=="ONE2ONE"){

      $visibleColumnTexts = $this->getOption('visible_column_text'); //visible_column_text
      //su dung cho popup
      $theadPopup = '<thead><tr><th>&nbsp;</th>';
      foreach ($visibleColumnTexts as $column) {
        $theadPopup = $theadPopup . '<th>' . $column . '</th>';
      }
      $theadPopup = $theadPopup . '</tr></thead>';
      //su dung cho hien thi tren page
      $theadPage = $theadPopup;

      $dataInfo = null;
      if(is_null($value)==false){
        $dataInfo =  self::getOne($value);//du lieu de hien thi ra neu da ton tai
//        if(count($dataInfo)>0){
//            if(is_null($this->getOption("table_relate_array"))==false){
//              $table_relate_array = $this->getOption("table_relate_array");
//              $column_relate_array = $this->getOption("column_relate_array");
//              $vtSingers =$dataInfo[0][$table_relate_array]; //id,name,alias (substring=3)VD: VtSinger
//              $alias = "";
//              foreach ($vtSingers as $singer) {
//		  if($table_relate_array=="VtSinger"){
//		      if(array_key_exists("is_active",$singer)){
//			if($singer["is_active"]==true){
//			    $alias = $alias . ", " . $singer[$column_relate_array];//singerName
//			}
//		      }else{
//			  $alias = $alias . ", " . $singer[$column_relate_array];//singerName
//		      }
//	          }else{
//		      $alias = $alias . ", " . $singer[$column_relate_array];//singerName
//		  }
//              }
//	      $alias = ltrim($alias,",");
//	      echo $alias; die();
//              $dataInfo[0][$column_relate_array] = $alias;
//            }
        }
//      }
      $textInfo = "";
      $isVtTableShowed = "";//display:block hoac display:none;
      $tbodyPage = "<tbody><tr></tr></tbody>"; //ko co du lieu
      if(is_null($dataInfo)||count($dataInfo)==0){
        $isVtTableShowed = 'style="display:none;"';
      }else{
        $currentPostId = $this->generateId($name);
        $rowId = $dataInfo[0]["id"];
        $tbodyPage = '<tbody><tr><td style="width:10px;"><input type="button" class="btn" value="Xoá" onclick="vtRemoveDataFromModal(\''.$currentPostId.'\','.$rowId.')" /></td>';
        foreach ($visibleColumns as $column) {
          $textInfo = $textInfo . '<td><span title="'.VtHelper::encodeOutput($dataInfo[0][$column],true).'">'. VtHelper::getLimitStringWithoutEncode($dataInfo[0][$column], 50) . '</span></td>';
        }
        $tbodyPage = $tbodyPage. $textInfo."</tr></tbody>";
      }

      $template_vars = array(
        '{input.isVtTableShowed}' => $isVtTableShowed,
        '{input.theadPage}' => $theadPage,
        '{input.theadPopup}' => $theadPopup,
        '{input.tbodyPage}' => $tbodyPage,
        '{button.choice_button_text}' => $this->getOption('choice_button_text'),
        '{input.id}' => $this->generateId($name),
        '{input.button_text}' => $this->getOption('button_text'),
        '{input.modal_header}' => $this->getOption('modal_header'),
        '{input.modal_text}' => $this->getOption('modal_text'),
        '{input.keyword}' => $i18n->__("Từ khóa"),
        '{input.search_button_text}' => $i18n->__("Tìm kiếm"),
        '{input.search_method}' => $this->getOption('search_method'),
        '{input.relation_type}' => $this->getOption('relation_type'),
        '{input.previous_button_text}' => $this->getOption('previous_button_text'),
        '{input.next_button_text}' => $this->getOption('next_button_text'),
        '{input.close_button_text}' => $this->getOption('close_button_text'),
        '{input.save_button_text}' => $this->getOption('save_button_text'),
      );

      $hidden_main = new sfWidgetFormInputHidden;
      $template_vars['{input.main_field}'] = $hidden_main->render($name, $value);

    }

    // define main template variables




    $hidden_modal_current_page = new sfWidgetFormInputHidden;
    $template_vars['{input.modal_current_page}'] = $hidden_modal_current_page->render($this->generateId($name) . "_modal_current_page", "1");

    // merge templates and variables
    return strtr(
                    $this->getOption('template.html') . $this->getOption('template.javascript'), $template_vars
    );
  }

  private function getRecords($value) {
    $records = array();
    if (false !== $this->getOption('add_empty')) {
      $records[''] = true === $this->getOption('add_empty') ? '' : $this->translate($this->getOption('add_empty'));
    }

    $tableMethod = $this->getOption('table_method');
    $relationType = $this->getOption('relation_type');

    if ($relationType == "FIELD"){
        if(is_null($value)==false && $value!=""){
          $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod(explode(',', $value));
        }else{
          return array();
        }
    }
    else {
      //quan he 1 nhieu bat buoc $value phai co kieu array
      if(is_null($value)==false && count($value)>0){
        $tableMethodInput = $this->getOption('table_method_input');
        //khong can su dung tham so $tableMethodInput, nhung do 1 so nguoi chua sua lai nen phai if - else
        if(is_null($tableMethodInput)){
          $value2 = $this->getOption('second_param_for_query');
          if(is_null($value2)==false){
            $value2 = $this->getOption('second_param_for_query');
            $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod($value,$value2);
          }else{
            $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod($value);
          }
        }else if(is_null($tableMethodInput)==false){
          $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod($tableMethodInput);
        }
      }else{
        return array();
      }

    }

	  // HuongND16: Nếu có lấy trường is_active thì đổi câu thông báo
//	  $arrayResult2 = array();
//	  foreach($results->fetchArray() as $item){
//		  if(array_key_exists('is_active',$item)){
//			  if($item['is_active']) $item['is_active'] = 'Hiển thị';
//		    else $item['is_active'] = 'Không hiển thị';
//		  }
//		  array_push($arrayResult2, $item);
//	  }
    return $results->fetchArray();
//    return $arrayResult2;
  }

  //su dung cho ONE2ONE
  private function getOne($value) {
    $tableMethod = $this->getOption('table_method');
    if(is_null($value)==false && $value!=""){
      $results = Doctrine_Core::getTable($this->getOption('model'))->$tableMethod($value);
    }else{
      return array();
    }

//    return $results->fetchArray();
	  // HuongND16: Nếu có lấy trường is_active thì đổi câu thông báo
//	  $arrayResult2 = array();
//	  foreach($results->fetchArray() as $item){
//		  if(array_key_exists('is_active',$item)){
//			  if($item['is_active']) $item['is_active'] = 'Hiển thị';
//			  else $item['is_active'] = 'Không hiển thị';
//		  }
//		  array_push($arrayResult2, $item);
//	  }
	  return $results->fetchArray();
  }
}

?>
