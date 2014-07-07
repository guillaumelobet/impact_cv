<?

	$user = $_POST['username'];
	$paper = isset($_POST['paper']);
    $presentation = isset($_POST['presentation']);
    $poster = isset($_POST['poster']);
    
	// Get the JSON file and decode it
	$impacturl = "https://impactstory.org/profile/".$user;
	$json_str = file_get_contents($impacturl);
	$json = json_decode($json_str);
	
	$bibfile = "# Publication list of " . $json->about->given_name . " " . $json->about->surname . "\n";
	$bibfile = $bibfile . " File generated using the impact_cv widget, created by Guillaume Lobet (Université de Liège) \n\n";
	$bibfile = $bibfile . " Website: www.guillaumelobet.be/impact \n\n";
	$bibfile = $bibfile . " Source code: https://github.com/guillaumelobet/impact_cv \n\n";
	$bibfile = $bibfile . " " . date(DATE_RFC2822) . "\n \n";

	$products = $json->products;
	$key = 1;
	$key2 = 1;
	$key3 = 1;

	// Global metrics
	$tot_papers = 0;
	$tot_presentations = 0;
	$tot_posters = 0;
	$citations = 0;
	$readers = 0;
	$slideshare_views = 0;
	$slideshare_downloads = 0;
	$figshare_views = 0;
	$figshare_shares = 0;
	$hindex = array();

	// Compute the global metrics
	foreach($products as $prod){
		if(isset($prod->biblio)){
			if($paper){
				if(str::contains($prod->biblio->calculated_genre, 'article')){

					// Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m){	

						// Scopus citations
						if(str::contains($m->display_provider, 'Scopus')){
		  					$citations = $citations + $m->display_count; 
		  					array_push($hindex, $m->display_count);
		  				}

						// Mendeley metrics
						if(str::contains($m->display_provider, 'Mendeley')){
							// Readers
							if(str::contains($m->interaction, 'readers')){
		  						$readers = $readers + $m->display_count; 
		  					}
		  				}
	  				}
	  				$tot_papers = $tot_papers + 1;
  				}					
  			}
			if($presentation){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->calculated_genre, 'slides')){ 
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$figshare_views = $figshare_views + $m->display_count; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$figshare_shares = $figshare_shares + $m->display_count; 
							}
			  			}
						# Slideshare metrics
						if(str::contains($m->display_provider, 'Slideshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$slideshare_views = $slideshare_views + $m->display_count; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'downloads')){
								$slideshare_downloads = $slideshare_downloads + $m->display_count; 
							}
			  			}			  					

			  		}
					$tot_presentations = $tot_presentations + 1;	
			 	}  		
			}
			if($poster){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->calculated_genre, 'poster')){ 
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$figshare_views = $figshare_views + $m->display_count; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$figshare_shares = $figshare_shares + $m->display_count; 
							}
			  			}		  					
			  		}
					$tot_posters = $tot_posters + 1;	
			 	}  		
			 }				 
  		}
  	} 	

 	
 	// compute the h-index
 	$hi = 0;
  	rsort($hindex);
	foreach($hindex as $x=>$x_value) {
  		if($x_value < ($x+1)) $hi = $hi + 1;
	}


  	// Print the metrics
	$bibfile = $bibfile . "## Overview \n";	

	if($paper){
		$bibfile = $bibfile . " - Number of papers = " . $tot_papers . " \n";
		$bibfile = $bibfile . " - Total number of citations = " . $citations . " \n";
		$bibfile = $bibfile . " - Average number of citations = " . ($citations/$tot_papers) . " \n";
		$bibfile = $bibfile . " - Total number of Mendeley readers = " . $readers . " \n";
		if($hi > 0) $bibfile = $bibfile . " - h-index [Scopus] = " . $hi . " \n";
	}
	if($presentation) $bibfile = $bibfile . " - Number of presentations = " . $tot_presentations . " \n";
	if($poster) $bibfile = $bibfile . " - Number of posters = " . $tot_posters . " \n";
	if($poster || $presentation){
		if($figshare_views > 0) $bibfile = $bibfile . " - Total number of figshare views = " . $figshare_views . " \n";
		if($figshare_shares > 0) $bibfile = $bibfile . " - Total number of figshare shares = " . $figshare_shares . " \n";
	}
	if($presentation){
		if($slideshare_views > 0) $bibfile = $bibfile . " - Total number of Slideshare views = " . $slideshare_views . " \n";
		if($slideshare_downloads > 0) $bibfile = $bibfile . " - Total number of Slideshare downloads = " . $slideshare_downloads. " \n";
	}
	$bibfile  =$bibfile . "\n";
	// Get the papers
	if($paper) $bibfile = $bibfile . "## Papers \n";	
	foreach($products as $prod){
		if(isset($prod->biblio)){
			if($paper){
				if(str::contains($prod->biblio->calculated_genre, 'article')){


					// Get the missing information in PubMed
					// Initialize the values
					$vol = "0000";
					$issue = "0000";
					$pp = "0000";
					
					// Get the missing inforation on pubmed
					if(1==0){
					try {

						if(isset($prod->aliases->pmid)){
							$pmid = $prod->aliases->pmid[0];
							$pmid = substr($pmid, -9, 8);

							// Get and Parse the XML file
							$aurl = "http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=$pmid&retmode=xml";									
							$xml_str = file_get_contents($aurl);
							$xml = new SimpleXMLElement($xml_str);
							$art = $xml->PubmedArticle->MedlineCitation->Article;

							// access XML data
							if(isset($art->Journal->JournalIssue->Volume)) $vol = $art->Journal->JournalIssue->Volume;
							if(isset($art->Journal->JournalIssue->Issue)) $issue = $art->Journal->JournalIssue->Issue;
							if(isset($art->Pagination->MedlinePgn)) $pp = $art->Pagination->MedlinePgn;
						}

					} catch(Exception $e) {};	
					}			

					// Print the general article information
			  		$bibfile = $bibfile . $key . '. **'. $prod->biblio->display_title . "** \n";
					$bibfile = $bibfile . $prod->biblio->authors;
			  		$bibfile = $bibfile . " (" . $prod->biblio->display_year . "), ";
			  		$bibfile = $bibfile . $prod->biblio->journal . ", ";
			  		if($vol != "0000") $bibfile = $bibfile . $vol . ":" . $issue . ", ";
			  		if($pp != "0000") $bibfile = $bibfile . "pp. " . $pp . " \n";

				
					// Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m){	

						// Scopus citations
						if(str::contains($m->display_provider, 'Scopus')){
		  					$bibfile = $bibfile . ", Scopus citations = " . $m->display_count . ", "; 
		  				}

						// Mendeley metrics
						if(str::contains($m->display_provider, 'Mendeley')){

							// Readers
							if(str::contains($m->interaction, 'readers')){
		  						$bibfile = $bibfile . "Mendeley readers = " . $m->display_count . ""; 
		  					}
		  				}
	  				}
			  		$bibfile = $bibfile . "\n \n";	  				
	  				$key = $key + 1;
  				}					
  			} 
  		}
  	} 			
			

  	# Get the presentations
  	if($presentation) $bibfile = $bibfile . "## Presentations \n";
	foreach($products as $prod){
		if(isset($prod->biblio)){
  			if($presentation){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->calculated_genre, 'slides')){ //&& str::contains($prod->biblio->repository, 'figshare')){

					# Get the general informations
			  		$bibfile = $bibfile . $key2 . '. **' . $prod->biblio->title . "**, \n";
			  		$bibfile = $bibfile . $prod->biblio->repository . ", ";
			  		if(isset($prod->biblio->year)) $bibfile = $bibfile . '(' . $prod->biblio->year . ") ";
					
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . ", views = " . $m->display_count; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$bibfile = $bibfile . ", shares = " . $m->display_count; 
							}
			  			}
						# Slideshare metrics
						if(str::contains($m->display_provider, 'Slideshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . ", views = " . $m->display_count; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'downloads')){
								$bibfile = $bibfile . ", downloads = " . $m->display_count; 
							}
			  			}			  					

			  		}
			  		$bibfile = $bibfile . "\n \n";
					$key2 = $key2 + 1;	
			 	}
  			}
  		}
  	}

  	# Get the posters
	if($poster) $bibfile = $bibfile . "## Posters \n";  		
	foreach($products as $prod){
		if(isset($prod->biblio)){
  			if($poster){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->calculated_genre, 'poster') && str::contains($prod->biblio->repository, 'figshare')){

					# Get the general informations
			  		$bibfile = $bibfile . $key3 . '. **' . $prod->biblio->title . "** \n";
			  		$bibfile = $bibfile . $prod->biblio->repository . ", ";
			  		if(isset($prod->biblio->year)) $bibfile = $bibfile . '(' . $prod->biblio->year . ") ";

					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . ", views = " . $m->display_count; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$bibfile = $bibfile . ", shares = " . $m->display_count; 
							}
			  			}		

			  		}
			  		$bibfile = $bibfile . " \n \n";
					$key3 = $key3 + 1;	
			 	}
  			}
		}
	}
	echo $bibfile;

		$fname = dirname(__FILE__).'/cv/impact_cv_'.$user.'.md';
		file_put_contents($fname , $bibfile);
		
		if (file_exists($fname)) {
	    	header('Content-Description: File Transfer');
	    	header('Content-Type: application/octet-stream');
	    	header('Content-Disposition: attachment; filename='.basename($fname));
	    	header('Expires: 0');
	    	header('Cache-Control: must-revalidate');
	    	header('Pragma: public');
	    	header('Content-Length: ' . filesize($fname));
	    	ob_clean();
	    	flush();
	    	readfile($fname);
			unlink($fname);	
		}

?>