<?

	$user = $_POST['username'];
	$pdf = $_POST['export'] == "pdf";
	$paper = isset($_POST['paper']);
    $presentation = isset($_POST['presentation']);
    $poster = isset($_POST['poster']);
    
	// Get the JSON file and decode it
	$impacturl = "https://v1.impactstory.org/".$user;
	$json_str = file_get_contents($impacturl);
	$json = json_decode($json_str);
	

	// Start writing the file
	$bibfile = "<html>";

	// Set the styles
	$bibfile = $bibfile . 
	"<style>
		body{
			margin: 50px;
		}
		h1{
			padding-left: 20px;
		}
		h2{
			padding-left: 20px;
			color: #28353A;
		}
		b{

		}
		.overview{
			padding-left: 40px;
		}
		.metrics{
			padding-left: 40px;
		}
		.bibitem{
			padding-left: 60px;
			margin-bottom: 20px;	
		}
		.metric{

		}
		.auth{

		}
		.num{
			margin-left: -20px;
		}
		.title{
			font-weight: bold;
		}
		.journ{

		}
		.year{

		}
	</style>";

	$bibfile = $bibfile . "<body>\n";
	$bibfile = $bibfile . "<h1> Publication list of " . $json->about->given_name . " " . $json->about->surname . "</h1>\n";
	$bibfile = $bibfile . "<div class='overview'>\n";
	$bibfile = $bibfile . " File generated using the impact_cv widget, created by Guillaume Lobet (Université de Liège) <br>\n";
	$bibfile = $bibfile . " Website: <a href='http://www.guillaumelobet.be/impact'>www.guillaumelobet.be/impact</a><br>\n";
	$bibfile = $bibfile . " Source code: <a href='https://github.com/guillaumelobet/impact_cv'>github.com/guillaumelobet/impact_cv</a><br>\n";
	$bibfile = $bibfile . " " . date(DATE_RFC2822) . "\n";
	$bibfile = $bibfile . "</div>\n\n";


if(1==1){
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
				if(str::contains($prod->biblio->genre, 'article')){

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
				if(str::contains($prod->biblio->genre, 'slides')){ 
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
				if(str::contains($prod->biblio->genre, 'slides')){ 
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
	$bibfile = $bibfile . "<h2> Overview </h2> \n";	
	$bibfile = $bibfile . "<div class='metrics'> \n";	
	if($paper){
		$bibfile = $bibfile . " Number of papers = <b>" . $tot_papers . "</b><br>\n";
		$bibfile = $bibfile . " Total number of citations = <b>" . $citations . "</b><br>\n";
		$bibfile = $bibfile . " Average number of citations = <b>" . ($citations/$tot_papers) . "</b><br>\n";
		$bibfile = $bibfile . " Total number of Mendeley readers = <b>" . $readers . "</b><br>\n";
		if($hi > 0) $bibfile = $bibfile . " h-index [Scopus] = <b>" . $hi . "</b><br>\n";
	}
	if($presentation) $bibfile = $bibfile . " Number of presentations = <b>" . $tot_presentations . "</b><br>\n";
	if($poster) $bibfile = $bibfile . " Number of posters = <b>" . $tot_posters . "</b><br>\n";
	if($poster || $presentation){
		if($figshare_views > 0) $bibfile = $bibfile . " Total number of figshare views = <b>" . $figshare_views . "</b><br>\n";
		if($figshare_shares > 0) $bibfile = $bibfile . " Total number of figshare shares = <b>" . $figshare_shares . "</b><br>\n";
	}
	if($presentation){
		if($slideshare_views > 0) $bibfile = $bibfile . " Total number of Slideshare views = <b>" . $slideshare_views . "</b><br>\n";
		if($slideshare_downloads > 0) $bibfile = $bibfile . " Total number of Slideshare downloads = <b>" . $slideshare_downloads. "</b><br>\n";
	}
	$bibfile = $bibfile . "</div>\n\n";	



	// Get the papers
	if($paper) $bibfile = $bibfile . "<h2> Papers </h2>\n";	
	foreach($products as $prod){
		if(isset($prod->biblio)){
			if($paper){
				if(str::contains($prod->biblio->genre, 'article')){


					// Get the missing information in PubMed
					// Initialize the values
					$vol = "0000";
					$issue = "0000";
					$pp = "0000";
					
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
			  		$bibfile = $bibfile . "<div class='bibitem'>";
			  		$bibfile = $bibfile . "<span class='num'>" . $key . '.</span> <span class="title">'. $prod->biblio->title . "</span><br>";
					$bibfile = $bibfile . "<span class='auth'> " . $prod->biblio->authors . "</span>";
			  		$bibfile = $bibfile . "<span class='year'> " . " (" . $prod->biblio->year . ")</span> ";
			  		$bibfile = $bibfile . "<span class='journ'> " . $prod->biblio->journal . "</span> ";
			  		if(!str::contains($vol, "0000")) $bibfile = $bibfile . ",<span class='vol'>" . $vol . "</span>";
			  		if(!str::contains($issue, "0000")) $bibfile = $bibfile . ":<span class='iss'>". $issue . "</span> ";
			  		if(!str::contains($pp, "0000")) $bibfile = $bibfile . ",<span class='pages'>". "pp. " . $pp . "</span>";
			  		$bibfile = $bibfile . "<br>\n";
				
					// Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m){	

						// Scopus citations
						if(str::contains($m->display_provider, 'Scopus')){
		  					$bibfile = $bibfile . "<span class='metric'>Scopus citations = <b>" . $m->display_count . "</b></span><br>\n"; 
		  				}

						// Mendeley metrics
						if(str::contains($m->display_provider, 'Mendeley')){

							// Readers
							if(str::contains($m->interaction, 'readers')){
		  						$bibfile = $bibfile . "<span class='metric'>Mendeley readers = <b>" . $m->display_count . "</b></span><br>"; 
		  					}
		  				}
	  				}
	  				$bibfile = $bibfile . "\n</div>\n\n";			
	  				$key = $key + 1;
  				}					
  			} 
  		}
  	} 			
			

  	# Get the presentations
  	if($presentation) $bibfile = $bibfile . "<h2>Presentations </h2>\n";
	foreach($products as $prod){
		if(isset($prod->biblio)){
  			if($presentation){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->genre, 'slides')){ //&& str::contains($prod->biblio->repository, 'figshare')){

					# Get the general informations
			  		$bibfile = $bibfile . "<div class='bibitem'>";	
			  		$bibfile = $bibfile . "<span class='num'>" . $key2 . '.</span> <span class="title">'. $prod->biblio->title . "</span><br>";
			  		$bibfile = $bibfile . "<span class='journ'>" . $prod->biblio->repository . "</span>, ";
			  		if(isset($prod->biblio->year)) $bibfile = $bibfile . "<span class='year'>" . " (" . $prod->biblio->year . ")</span><br>\n";
					
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . "<span class='metric'>figshare views = <b>" . $m->display_count . "</b></span><br>"; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$bibfile = $bibfile . "<span class='metric'>figshare shares = <b>" . $m->display_count . "</b></span><br>";
							}
			  			}
						# Slideshare metrics
						if(str::contains($m->display_provider, 'Slideshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . "<span class='metric'>Slideshare iews = <b>" . $m->display_count . "</b></span><br>"; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'downloads')){
								$bibfile = $bibfile . "<span class='metric'>Slideshare downloads = <b>" . $m->display_count . "</b></span><br>"; 
							}
			  			}			  					

			  		}
	  				$bibfile = $bibfile . "\n</div>\n\n";			
					$key2 = $key2 + 1;	
			 	}
  			}
  		}
  	}

  	# Get the posters
	if($poster) $bibfile = $bibfile . "<h2>Posters</h2> \n";  		
	foreach($products as $prod){
		if(isset($prod->biblio)){
  			if($poster){
				// Print the presentations (from figshare)
				if(str::contains($prod->biblio->genre, 'poster') && str::contains($prod->biblio->repository, 'figshare')){

					# Get the general informations
			  		$bibfile = $bibfile . "<div class='bibitem'>";	
			  		$bibfile = $bibfile . "<span class='num'>" . $key3 . '.</span> <span class="title">'. $prod->biblio->title . "</span><br>";
			  		$bibfile = $bibfile . "<span class='journ'>" . $prod->biblio->repository . "</span>, ";
			  		if(isset($prod->biblio->year)) $bibfile = $bibfile . "<span class='year'>" . " (" . $prod->biblio->year . ")</span>, ";
					
					# Get the metrics
					$metrics = $prod->metrics;
					foreach($metrics as $m) {	

						# Figshare metrics
						if(str::contains($m->display_provider, 'Figshare')){
			  				
			  				# Figshare views
			  				if(str::contains($m->interaction, 'views')){
								$bibfile = $bibfile . "<span class='metric'>figshare views = <b>" . $m->display_count . "</b></span><br>"; 
							}
							# Figshare shares
			  				if(str::contains($m->interaction, 'shares')){
								$bibfile = $bibfile . "<span class='metric'>figshare shares = <b>" . $m->display_count . "</b></span><br>"; 
							}
			  			}		

			  		}
	  				$bibfile = $bibfile . "\n</div>\n\n";			
					$key3 = $key3 + 1;	
			 	}
  			}
		}
	}
}
	$bibfile = $bibfile . "</body></html>";

	if($pdf){
    	$html2pdf = new HTML2PDF('P','A4','fr');
		$html2pdf->WriteHTML($bibfile);
    	$html2pdf->Output('impact_cv_'.$user.'.pdf');
    }
  
    else{
		$fname = dirname(__FILE__).'/cv/impact_cv_'.$user.'.html';
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
	}


?>
