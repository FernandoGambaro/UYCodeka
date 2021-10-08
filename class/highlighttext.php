<?php
class HighLightText
{ 
	/*/ these two consecutive functions are useful for search and customize the found text . */
	public function matchAndReplaceText($keyword,$string ,$condition=NULL)
	{ 
		/*/if the search Keyword is empty then return with the string  , do not go any further */
		if(empty($keyword))
		{ 
			return $string; 
		}
		/*/omit any html tags if present other wise it will create conflict.*/
		$string = strip_tags($string); 
		/*/ to check if the search keyword contains space ,it breaks into the words and search for each words.*/  
		$keyword= $this->getArrayofStringWithNoSpace($keyword);
		
			/*/ above function return string either in array or string. */
		if(is_array($keyword))
		{ 
			/*/ replaces the main string with the all the words return by the array of the above functions .*/ 
			foreach($keyword as $text)
			{ 
					if(eregi($text,$string))
					{ 
						$string = eregi_replace($text,"<span class='foundText'>".$text."</span>",$string); 
					} 
			} 
			return $string; 
		} 
		/*/ if the above function returns the string then it only check the string with the returned string.*/ 
		else
		{ 
			if(eregi($keyword,$string) and !empty($keyword))
				return eregi_replace($keyword,"<span class='foundText'>".$keyword."</span>",$string); 
			else 
				return $string; 
		} 
	} 
	/*/ this function breaks the string by words by finding the whitespace and returns all the words into the array .*/ 
	 private  function getArrayofStringWithNoSpace($string)
	{ 
		/*/ checks if the string contains whitespace.*/
		$newArray = array(); 
		if(eregi(' ',$string))
		{ 
			/*//splits the string by whitespace and returns into array*/
			$ar =  explode(' ',$string); 
			foreach($ar  as $val)
			{ 
				/*/ checks if the array value contais words or white space */
				if(!empty($val))
					$newArray[] = $val; 
			}  
			return $newArray; 	
		}
		else 
		{ 
			$newArray[] = $string; 
			return $newArray; 
		}  
	} 
} 
?>