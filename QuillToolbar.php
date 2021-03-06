<div class="ql-format-group">
    <select title="Font" class="ql-font">
        <option value="sans-serif" selected>黑体</option>
        <option value="Georgia, serif">宋体</option>
        <option value="Monaco, 'Courier New', monospace">微软雅黑</option>
    </select>
    <select title="Size" class="ql-size">
        <option value="10px">10px</option>
        <option value="14px" selected>14px</option>
        <option value="18px">18px</option>
        <option value="24px">24px</option>
        <option value="32px">32px</option>
    </select>
</div>
<div class="ql-format-group">
    <span title="Bold" class="ql-format-button ql-bold"></span>
    <span class="ql-format-separator"></span>
    <span title="Italic" class="ql-format-button ql-italic"></span>
    <span class="ql-format-separator"></span>
    <span title="Underline" class="ql-format-button ql-underline"></span>
    <span class="ql-format-separator"></span>
    <span title="Strikethrough" class="ql-format-button ql-strike"></span>
</div>
<div class="ql-format-group">
    <select title="Text Color" class="ql-color">
        <option value="rgb(0, 0, 0)" selected></option>
        <option value="rgb(230, 0, 0)"></option>
        <option value="rgb(255, 153, 0)"></option>
        <option value="rgb(255, 255, 0)"></option>
        <option value="rgb(0, 138, 0)"></option>
        <option value="rgb(0, 102, 204)"></option>
        <option value="rgb(153, 51, 255)"></option>
        <option value="rgb(255, 255, 255)"></option>
        <option value="rgb(250, 204, 204)"></option>
        <option value="rgb(255, 235, 204)"></option>
        <option value="rgb(255, 255, 204)"></option>
        <option value="rgb(204, 232, 204)"></option>
        <option value="rgb(204, 224, 245)"></option>
        <option value="rgb(235, 214, 255)"></option>
        <option value="rgb(187, 187, 187)"></option>
        <option value="rgb(240, 102, 102)"></option>
        <option value="rgb(255, 194, 102)"></option>
        <option value="rgb(255, 255, 102)"></option>
        <option value="rgb(102, 185, 102)"></option>
        <option value="rgb(102, 163, 224)"></option>
        <option value="rgb(194, 133, 255)"></option>
        <option value="rgb(136, 136, 136)"></option>
        <option value="rgb(161, 0, 0)"></option>
        <option value="rgb(178, 107, 0)"></option>
        <option value="rgb(178, 178, 0)"></option>
        <option value="rgb(0, 97, 0)"></option>
        <option value="rgb(0, 71, 178)"></option>
        <option value="rgb(107, 36, 178)"></option>
        <option value="rgb(68, 68, 68)"></option>
        <option value="rgb(92, 0, 0)"></option>
        <option value="rgb(102, 61, 0)"></option>
        <option value="rgb(102, 102, 0)"></option>
        <option value="rgb(0, 55, 0)"></option>
        <option value="rgb(0, 41, 102)"></option>
        <option value="rgb(61, 20, 102)"></option>
    </select>
    <span class="ql-format-separator"></span>
    <select title="Background Color" class="ql-background">
        <option value="rgb(0, 0, 0)"></option>
        <option value="rgb(230, 0, 0)"></option>
        <option value="rgb(255, 153, 0)"></option>
        <option value="rgb(255, 255, 0)"></option>
        <option value="rgb(0, 138, 0)"></option>
        <option value="rgb(0, 102, 204)"></option>
        <option value="rgb(153, 51, 255)"></option>
        <option value="rgb(255, 255, 255)" selected></option>
        <option value="rgb(250, 204, 204)"></option>
        <option value="rgb(255, 235, 204)"></option>
        <option value="rgb(255, 255, 204)"></option>
        <option value="rgb(204, 232, 204)"></option>
        <option value="rgb(204, 224, 245)"></option>
        <option value="rgb(235, 214, 255)"></option>
        <option value="rgb(187, 187, 187)"></option>
        <option value="rgb(240, 102, 102)"></option>
        <option value="rgb(255, 194, 102)"></option>
        <option value="rgb(255, 255, 102)"></option>
        <option value="rgb(102, 185, 102)"></option>
        <option value="rgb(102, 163, 224)"></option>
        <option value="rgb(194, 133, 255)"></option>
        <option value="rgb(136, 136, 136)"></option>
        <option value="rgb(161, 0, 0)"></option>
        <option value="rgb(178, 107, 0)"></option>
        <option value="rgb(178, 178, 0)"></option>
        <option value="rgb(0, 97, 0)"></option>
        <option value="rgb(0, 71, 178)"></option>
        <option value="rgb(107, 36, 178)"></option>
        <option value="rgb(68, 68, 68)"></option>
        <option value="rgb(92, 0, 0)"></option>
        <option value="rgb(102, 61, 0)"></option>
        <option value="rgb(102, 102, 0)"></option>
        <option value="rgb(0, 55, 0)"></option>
        <option value="rgb(0, 41, 102)"></option>
        <option value="rgb(61, 20, 102)"></option>
    </select>
</div>
<div class="ql-format-group">
    <span title="Bullet" class="ql-format-button ql-bullet"></span>
    <span class="ql-format-separator"></span>
    <span title="List" class="ql-format-button ql-list"></span>
    <span class="ql-format-separator"></span>
    <select title="Text Alignment" class="ql-align">
        <option value="left" selected></option>
        <option value="center"></option>
        <option value="right"></option>
        <option value="justify"></option>
    </select>
</div>
<div class="ql-format-group">
    <span title="Link" class="ql-format-button ql-link"></span>
    <span class="ql-format-separator"></span>
    <span title="Image" class="ql-format-button ql-image"></span>
    <?php if (isset($options["upload"])) {?>
    <span class="ql-format-separator"></span>
    <span title="Upload" class="ql-format-button" data-toggle="upload">
        <i class="fa fa-cloud-upload"></i>
    </span>
    <?php }?>
</div>
