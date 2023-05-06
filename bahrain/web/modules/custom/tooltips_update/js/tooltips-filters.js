(function ($,Drupal,drupalSettings) {
  // Getting arrays from drupalSettings.
    function common_arrays(){
      let input_array = drupalSettings.tooltips_update;
      let shopping = input_array['shopping_tooltips_array'];
      let services = input_array['services_tooltips_array'];
      let dining = input_array['dinning_tooltips_array'];
      let galleries = input_array['galleries_tooltips_array'];
      let lounges = input_array['lounges_tooltips_array'];
      let inputEnArray = {
        'All': '',
        'Shopping': shopping,
        'Services': services,
        'Dining & Restaurants': dining,
        'Art Galleries': galleries,
        'Lounges & Hotel': lounges,
      };
      let inputArArray = {
        'الكل': '',
        'التسوّق': shopping,
        'الخدمات': services,
        'المطاعم والمقاهي': dining,
        'الفنون، المعارض': galleries,
        'صالات الاستراحة والفندق': lounges,
      };
      return [inputEnArray, inputArArray];
    }
 // Get not empty keys from array.
  function getNotEmptyKeys($array) {
    Object.keys($array).forEach(key => {
        if (($array[key]) === undefined) {
          delete $array[key];
        }
      }
    )
    return $array;
  }

  $(function () {
    // Get array with right keys.
    let array = common_arrays();
    let engArray = getNotEmptyKeys(array[0]);
    let arArray = getNotEmptyKeys(array[1]);
    // Get current lang.
    let langCode = drupalSettings.path.currentLanguage;
    // Building select lists.
    if (langCode === 'ar') {
      buildSelectList(arArray);
    } else {
      buildSelectList(engArray);
    }
    // Using select handler dependent from specific list.
    function selectHandler(obj){
    // Check math values for displaying.
      const tooltips = document.querySelectorAll('[class="tip"]');
      let tooltips_array = Array.from(tooltips);
      let currentOptionValue = obj.target.value;
      if (currentOptionValue !== 'All' && currentOptionValue !== 'الكل' ){
        for (let i = 0; i < tooltips_array.length; i++){
          let attribute = Array.from(tooltips)[i].getAttribute('data-nid');
          if (langCode === 'ar') {
            if (arArray[currentOptionValue].includes(attribute)){
              $(Array.from(tooltips)[i]).css("display","block");
            }else {
              $(Array.from(tooltips)[i]).css("display","none");
            }
          } else {
            if (engArray[currentOptionValue].includes(attribute)){
              $(Array.from(tooltips)[i]).css("display","block");
            }else {
              $(Array.from(tooltips)[i]).css("display","none");
            }
          }
        }
      }else {
        for (let i = 0; i < tooltips_array.length; i++){
          $(Array.from(tooltips)[i]).css("display","block");
        }
      }
    }
    // Provide building select list on html page.
    function buildSelectList($array) {
      let myParent = document.getElementById('block-bahrain-page-title');
      let objectKeys = Object.keys($array);
      let selectList = document.createElement("select");
      selectList.id = "placesSelect";
      myParent.appendChild(selectList);

      for (let i = 0; i < objectKeys.length; i++){
        const option = document.createElement("option");
        option.value = objectKeys[i];
        option.text = objectKeys[i];
        selectList.appendChild(option);
      }
      selectList.onchange = selectHandler;
    }

  })
})(jQuery,Drupal,drupalSettings);
