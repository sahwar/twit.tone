<?PHP
class twitTone {
  function __construct($hash) {
  if ($this->atom){$this->atom=null;}
  include_once "class.myatomparser.php";
  date_default_timezone_set('UTC');
  $this->twitter_hash=$hash;
  $this->debug=0;
  }
  private function getFeeds($time) {
  $t_hash=$this->twitter_hash;
  $data=new myAtomParser("http://search.twitter.com/search.atom?q=$t_hash&rpp=100");
  $raw=$data->getRawOutput();
  $count=0;
  foreach ($raw["FEED"]["ENTRY"] as $entry){$ts=strtotime($entry["PUBLISHED"]);if($ts<=strtotime($time)){}else{$count=$count+1;}}
  if ($this->debug>0){echo $count."<br>";}
  return $count;
  }
  
  public function returnTweets() {
  $count=$this->getFeeds("-5 minutes");
  $url="http://search.twitter.com/search.atom?q=".$this->twitter_hash."&rpp=".$count;
  if ($this->debug>0){echo $url."<br>";}
  $this->atom=new myAtomParser($url);
  $output = $this->atom->getOutput();
  if ($count>0){return $output;}else{return "no feeds in the last 5 minutes";}
  }
  
  public function countChars() {
  if (!$this->atom){$this->returnTweets();}
  $feeds=$this->atom->getRawOutput();
  foreach ($feeds["FEED"]["ENTRY"] as $entry) {$all=$all.$entry["CONTENT"];}
  return strlen($all);
  }
}
?>