<?php
class nfList {
    public $classAdd;
    public $id;
    public $function;
    function __construct() {
        $this->id='MetroList'.  uniqid();
        $this->items = array();
    }

    function addItem($item) {
        $this->items[] = $item;
    }
    function __toString() {
        $result='
<input type="text" id="filter" data-role="search">

<div class="boxscrolllv" id="'.$this->id.'_scroll" style="height: 300px;overflow:scroll" data-role="scrollbox">
<div class="listview set-border ' . $this->classAdd . '"data-view="content" data-role="listview" data-on-node-click="'.$this->function.'" id="'.$this->id.'">';
        foreach ($this->items as $item) {
            $result.=$item;
        }
        return "$result</div></div><script>$('#filter').keyup(function () {
    var filter = this.value.toLowerCase();  // no need to call jQuery here
    $('#".$this->id."').children().each(function() {
        /* cache a reference to the current .media (you're using it twice) */
        var _this = $(this);
        var title = _this.text().toLowerCase();

        /* 
            title and filter are normalized in lowerCase letters
            for a case insensitive search
         */
        if (title.indexOf(filter) < 0) {
            _this.hide();
        }else{
            _this.show();
        }
    });
});</script>"; 
    }
}

class nfListItem {
    public $icon;
    public $caption;
    public $content;

    function __construct($icon, $title,$content, $link, $classAdd = '') {
        $this->icon = $icon;
        $this->caption = $title;
        $this->link = $link;
        $this->content = $content;
        $this->classAdd = $classAdd;
    }
    function __toString() {
        return "<li".
            icontotag('data-icon',$this->icon).
            strtotag('data-caption',$this->caption).
            strtotag('data-content',$this->content).
            strtotag('data-value',$this->link).
        '></li>';
    }
}