<?php

  $my_hash_padding = 'When will we ever get to see the time when man is completely nice to his fellow donkeys huh.';


Function safeEscapeString($inp)
{
  if(is_array($inp))
    return array_map(__METHOD__, $inp);

// replace HTML tags '<>' with '[]'
  $temp1 = str_replace("<", "[", $inp);
  $temp2 = str_replace(">", "]", $temp1);

// but keep <br> or <br />
// turn <br> into <br /> so later it will be turned into ""
// using just <br> will add extra blank lines
  $temp1 = str_replace("[br]", "<br />", $temp2);
  $temp2 = str_replace("[br /]", "<br />", $temp1);

  if(!empty($temp2) && is_string($temp2))
  {
    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $temp2);
  }

  return $temp2;
}

Function validate_email($email)
{
  return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}

Function post_back_message($form_name)
{
  //$form_name is something like "Forgot password"
  $msg = "<br>Please close this window to return to the<br>'$form_name' form and correct your error.<br><br>";
  $msg .= "<br><form><input type='button' value=' Close Window ' onClick='window.close()'></form><br>";
  return($msg);
}

?>
