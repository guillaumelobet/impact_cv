<? if($_POST){

	$user = $_POST['username'];
	$paper = isset($_POST['paper']);
    $presentation = isset($_POST['presentation']);
    $poster = isset($_POST['poster']);
    
	// Get the JSON file and decode it
	$impacturl = "https://impactstory.org/profile/".$user;
	$json_str = file_get_contents($impacturl);
	$json = json_decode($json_str);
	
	$bibfile = "% File generated using the impact_cv widget, create by Guillaume Lobet (Université de Liège) \n";
	$bibfile = $bibfile . "% Website: www.guillaumelobet.be/impact \n";
	$bibfile = $bibfile . "% Source code: https://github.com/guillaumelobet/impact_cv \n";
	$bibfile = $bibfile . "% " . date(DATE_RFC2822) . "\n \n";

	$products = $json->products;
	$key = 1;
	$key2 = 1;
	$key3 = 1;


	foreach($products as $prod){
		if(isset($prod->biblio)){
			if($paper){
				if(str::contains($prod->biblio->genre, 'article')){


					// Get the missing information in PubMed
					// Initialize the values
					$vol = "";
					$issue = "";
					$pp = "";
					
					// Get the missing inforation on pubmed
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

					// Print the general article information
					$bibfile = $bibfile . '@article{article' . $key . ", \n";

					$bibfile = $bibfile . 'author = {' . $prod->biblio->authors . "}, \n";
			  		$bibfile = $bibfile . 'title = {{' . $prod->biblio->title . "}}, \n";
			  		$bibfile = $bibfile . 'journal = {' . $prod->biblio->journal . "}, \n";
			  		$bibfile = $bibfile . 'volume = {' . $vol . ":" . $issue . "}, \n";
			  		$bibfile = $bibfile . 'pages = {' . $pp . "}, \n";
			  		$bibfile = $bibfile . 'year = {' . $prod->biblio->year . "}, \n";
			  		$bibfile = $bibfile . 'keywords = {article} ';

				
					// Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m){	

						// Scopus citations
						if(str::contains($m->display_provider, 'Scopus')){
		  					$bibfile = $bibfile . ",\ncitations = {" . $m->display_count . "}"; 
		  				}

						// Mendeley metrics
						if(str::contains($m->display_provider, 'Mendeley')){

							// Readers
							if(str::contains($m->interaction, 'readers')){
		  						$bibfile = $bibfile . ",\nreaders = {" . $m->display_count . "}"; 
		  					}
		  				}
	  				}
					$bibfile = $bibfile . "}\n \n";
	  				$key = $key + 1;
  				}					
  			}  			
			

  			if($presentation){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->genre, 'slides')){ //&& str::contains($prod->biblio->repository, 'figshare')){

					# Get the general informations
					$bibfile = $bibfile . '@inproceedings{presentation' . $key2 . ", \n";
			  		$bibfile = $bibfile . 'title = {{' . $prod->biblio->title . "}}, \n";
			  		$bibfile = $bibfile . "booktitle = {" . $prod->biblio->repository . "}, \n";
			  		if(isset($prod->biblio->year)) $bibfile = $bibfile . 'year = {' . $prod->biblio->year . "}, \n";
			  		$bibfile = $bibfile . 'keywords = {presentation} ';
					
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . ",\nviews = {" . $m->display_count . "}"; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$bibfile = $bibfile . ",\nshares = {" . $m->display_count . "}"; 
							}
			  			}
						# Slideshare metrics
						if(str::contains($m->display_provider, 'Slideshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . ",\nviews = {" . $m->display_count . "}"; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'downloads')){
								$bibfile = $bibfile . ",\ndownloads = {" . $m->display_count . "}"; 
							}
			  			}			  					

			  		}
			  		$bibfile = $bibfile . "} \n \n";
					$key2 = $key2 + 1;	
			 	}
  			}
  		
  			if($poster){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->genre, 'poster') && str::contains($prod->biblio->repository, 'figshare')){

					# Get the general informations
					$bibfile = $bibfile . '@inproceedings{presentation' . $key2 . ", \n";
			  		$bibfile = $bibfile . 'title = {{' . $prod->biblio->title . "}}, \n";
			  		$bibfile = $bibfile . "booktitle = {figshare}, \n";
			  		$bibfile = $bibfile . 'year = {' . $prod->biblio->year . "}, \n";
			  		$bibfile = $bibfile . 'keywords = {presentation} ';
					
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . ",\nviews = {" . $m->display_count . "}"; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$bibfile = $bibfile . ",\nshares = {" . $m->display_count . "}"; 
							}
			  			}		

			  		}
			  		$bibfile = $bibfile . "} \n \n";
					$key2 = $key2 + 1;	
			 	}
  			}
		}
		}
	//echo dirname(__FILE__).'../../assets/cv/';
	$fname = dirname(__FILE__).'/cv/impact_cv_'.$user.'.bib';
	file_put_contents($fname , $bibfile);
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment; filename="'.$fname.'"');
	header('location:'.$fname);	
}
?>