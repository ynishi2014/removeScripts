<?php

$html = <<<HTML
<div style="color:red;">hoge</div>
<div
Onclick="alert(123)"
onmousedown="alert(123)"
ontheedge="hoge"
>hoge</div>
<Script>
console.log("hoge");
</script>
<script>
console.log("hoge");
</script>
HTML;

echo removeScriptTag($html);

function removeScriptTag($html){
  $dom = new DOMDocument;
  $dom->loadHTML($html);

  //scriptタグを除去
  $tags = $dom->getElementsByTagName('script');
  while($tags->length){
    $tag = $tags->item(0);
    $tag->parentNode->removeChild($tag);
  }

  //onから始まる属性を除去
  domWalker($dom);

  //不要なタグを除去 -- bodyの中だけ返す
  $bodyDOMNode = $dom->getElementsByTagName("body")->item(0);
  $bodyHTML = $bodyDOMNode->ownerDocument->saveHTML($bodyDOMNode);
  $bodyHTML = substr($bodyHTML, 6, -7);
  return $bodyHTML;
}
function domWalker($dom){
  if($dom->childNodes){
    foreach($dom->childNodes as $node){
      removeOnAttributes($node);
      domWalker($node);
    }
  }
}
function removeOnAttributes($node){
  if($node->attributes){
    for($i = 0; $i < $node->attributes->length; $i++){
      $attribute = $node->attributes->item($i);
      if(substr($attribute->name, 0, 2) == "on"){
        $node->removeAttribute($attribute->name);
        $i--;
      }
    }
  }
}
