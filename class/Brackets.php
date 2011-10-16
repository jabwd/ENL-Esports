<?php
// Changes made by Antwan van Houdt:
// - fixed some style issues
// - added alt attribute to img tag to fix HTML errors

class Brackets {
	
	private $teams, $rounds, $rows, $fields = array(), $list = array(), $teamsList = array(), $tag = '<img alt="" src="%s"/>', 
			$scoresList = array(), $teamtag = '<span class="team">%s</span>', $scoretag = '<div class="score">%s</div>';
	private $arrowup = 'resources/corner-bottom-left.gif', $arrowdown = 'resources/corner-top-left.gif', $connector = 'resources/connector-right.gif', $vline = 'resources/line-vertical.gif';
	
	public function __construct($teams, array $images = NULL)
	{
		$this->teams = $teams;
		
		if($this->checkIsMultiple())
		{
			if($images) $this->setImages($images);
			$this->getRounds();
			$this->getRows();
			$this->getFieldsToFill();
			$this->getFieldsToFillList();
		}
		else
		{
			ENLog('Error - the number <b>'.$this->teams.'</b> is not multiple of 2. Please choose correct teams number!');
			echo 'Not enough teams to start the bracket';
		}
	}
	
	private function getRounds()
	{
		$teams = $this->teams;
		$this->rounds = 1;
		
		while(!($teams%2))
		{
			$this->rounds++;
			$teams = $teams/2;
		}	
	}
	
	public function returnRounds()
	{
		return $this->rounds;	
	}
	
	private function getRows()
	{
		$this->rows = (2*$this->teams)-1;
	}
	
	public function returnRows()
	{
		return $this->rows;	
	}
	
	private function checkIsMultiple()
	{
		$temp = $this->teams;
		
		while(!($temp%2))
		{
			$temp = $temp/2;
		} 
		
		return $temp == 1 ? TRUE : FALSE;
	}
	
	private function getFieldsToFill()
	{		
		for($counter = 1; $counter <= $this->rounds; $counter++)
		{
			$this->fields[$counter]['round'] = $counter;
			$this->fields[$counter]['teams'] = $counter == 1 ? $this->teams : $this->fields[$counter-1]['teams']/2;
			$this->fields[$counter]['step'] = pow(2,$counter);
			$this->fields[$counter]['start'] = 0.5*$this->fields[$counter]['step'];
			
			$this->fields[$counter]['upper_arrow_start'] = $this->fields[$counter]['start'];
			$this->fields[$counter]['upper_arrow_step'] = 2*$this->fields[$counter]['step'];
			
			$this->fields[$counter]['down_arrow_start'] = $this->fields[$counter]['start']+$this->fields[$counter]['step'];
			$this->fields[$counter]['down_arrow_step'] = $this->fields[$counter]['upper_arrow_step'];
		}
	}
	
	private function getFieldsToFillList()
	{
		foreach($this->fields as $field)
		{			
			for($counter = 0; $counter < $field['teams']; $counter++)
			{
				$this->list[$field['round']]['teams'][$counter+1] = $field['start']+($counter*$field['step']);
			}	
			
			for($counter = 0; $counter < $field['teams']/2; $counter++)
			{
				if($field['teams'] > 1)
				{
					$this->list[$field['round']]['upper_arrows'][$counter+1] = $field['upper_arrow_start']+($counter*$field['upper_arrow_step']);
					$this->list[$field['round']]['down_arrows'][$counter+1] = $field['down_arrow_start']+($counter*$field['down_arrow_step']);
					$this->list[$field['round']]['connectors'][$counter+1] = 
					($this->list[$field['round']]['upper_arrows'][$counter+1]+$this->list[$field['round']]['down_arrows'][$counter+1])/2;
				}
			}
			
			if(isset($this->list[$field['round']]['upper_arrows']) and isset($this->list[$field['round']]['down_arrows']))
			{
				for($counter = 0; $counter < sizeof($this->list[$field['round']]['upper_arrows']); $counter++)
				{
					if(($this->list[$field['round']]['down_arrows'][$counter+1]-$this->list[$field['round']]['upper_arrows'][$counter+1])>2)
					{
						$start = $this->list[$field['round']]['upper_arrows'][$counter+1]+1;
						$stop = $this->list[$field['round']]['down_arrows'][$counter+1]-1;
						
						for($start; $start <= $stop; $start++)
						{
							if(!in_array($start, $this->list[$field['round']]['connectors']))
							{
								$this->list[$field['round']]['vlines'][] = $start;
							}
						}
					}
				}
			}
		}
	}
	
	public function showImages($row, $round)
	{
		
		if(isset($this->list[$round]['upper_arrows']))
		{
			if(in_array($row, $this->list[$round]['upper_arrows']))
			{
				return printf($this->tag, $this->arrowup);
			}
		}
		
		if(isset($this->list[$round]['down_arrows']))
		{
			if(in_array($row, $this->list[$round]['down_arrows']))
			{
				return printf($this->tag, $this->arrowdown);
			}
		}
		
		if(isset($this->list[$round]['connectors']))
		{
			if(in_array($row, $this->list[$round]['connectors']))
			{
				return printf($this->tag, $this->connector);
			}
		}
		
		if(isset($this->list[$round]['vlines']))
		{
			if(in_array($row, $this->list[$round]['vlines']))
			{
				return printf($this->tag, $this->vline);
			}
		}
	}
	
	public function showTeams($row, $round)
	{
		if(in_array($row, $this->list[$round]['teams']))
		{
			if(isset($this->teamsList[$round][$row]))
			{
				if(is_array($this->teamsList[$round][$row]))
				{
					
					$name = $this->teamsList[$round][$row]['name'];
					$score = $this->teamsList[$round][$row]['score'];
					
					if( strlen($name) > 20 )
					{
						echo ' class="hiddenBracket"';
					}
					else
					{
						echo ' class="filled">';
					}

					
					printf($this->teamtag, $name);
					printf($this->scoretag, $score);
					
				}
				else
				{
					printf($this->teamtag, $this->teamsList[$round][$row]);
				}
			}
			else
			{
				echo ' class="filled">';
				printf(' <b><i>-tba-</i></b>');
			}
		}
		else
		{
			echo ' class="empty">';
		}
	}
	
	public function setImages(array $images)
	{
		if(isset($images['arrowup']) and !empty($images['arrowup'])) $this->arrowup 		= $images['arrowup'];
		if(isset($images['arrowdown']) and !empty($images['arrowdown'])) $this->arrowdown 	= $images['arrowdown'];
		if(isset($images['connector']) and !empty($images['connector'])) $this->connector 	= $images['connector'];
		if(isset($images['vline']) and !empty($images['vline'])) $this->vline 				= $images['vline'];
	}
	
	public function addTeams(array $teams)
	{
		foreach($teams as $round => $values)
		{
			foreach($values as $key => $team)
			{
				if(isset($this->list[$round]['teams'][$key+1]))
				{
					$sortedvalues[$this->list[$round]['teams'][$key+1]] = $team;
				}
			}
			
			$this->teamsList[$round] = $sortedvalues;
		}
		
	}
}
?>