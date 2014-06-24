<?php snippet('header') ?>


<section class="content blogarticle">

	<article>
  	<?php 

      if(!empty($_POST['export'])){
        if($_POST['export'] == "latex"){include('impact-cv.php');}
        if($_POST['export'] == "markdown"){include('impact-cv-md.php');} 
        if($_POST['export'] == "html" || $_POST['export'] == "pdf"){include('impact-cv-html.php');} 
      }
    ?>

<h3> Generate your CV from ImpactStory</h3>

	<form class="searchtag" id="username" method="post" action="<?php echo thisURL() ?>">

	  <input type="text" id="username" placeholder="username" name="username" <?php if($_POST) echo "value=".$user?> />
		<input type="submit" value=" Generate "/></br>
  
    <select name="export" style="width: 30%">
      <option value="pdf">PDF</option>
      <option value="html">HTML</option>
      <option value="markdown">Markdown</option>
      <option value="latex" >LaTeX</option>
    </select>
  </br>

		<input type="checkbox" name="paper" value="paper" <?php if(isset($_POST['paper'])) echo 'checked'?>>Papers<br>
		<input type="checkbox" name="presentation" value="presentation" <?php if(isset($_POST['presentation'])) echo 'checked'?>>Presentations</br>
		<input type="checkbox" name="poster" value="poster" <?php if(isset($_POST['poster'])) echo 'checked'?>>Posters</br>

	</form>

    
    <div class="toggle-wrap-curri ">
    <div class="toggle-clickme-curri">
      Where can I find my ImpactStory username?
    </div>
    <div class="toggle-box-curri">
      Your username is the one used in the URL of your profile. It should look like this:</br>

      www.impactstory.org/<b>username</b>
    </div></div> 

    <?php
    $counts = db::query('SELECT max(count) as c FROM conversions');
    $count = $counts[0]['c'];   
      if($_POST){
        echo "Bibliography file has been generated for user <b>". $user . "</b>.</br>";    
        db::insert('conversions', array('count' => ($count + 1), 'name' => $user, 'type' => $_POST['export'] )); 
  
      }   
   ?>

  </br>
    <hr>
    </br>
    LaTex files can be downloaded on <a href="https://github.com/guillaumelobet/impact_cv" target="_blank">Github</a>.</br>
    Direct download:
    <ol>
      <li><a href="https://www.dropbox.com/s/s3aqw8dz4r9uapk/curriculum.tex" target="_blank"> Curriculum template [.tex] </a></li>
      <li><a href="https://www.dropbox.com/s/sdhk4unmapbdunt/friggeri-cv.cls" target="_blank"> Class file [.cls] </a></li>
    <ol>
  </br>
  Any issues or questions: <a href="https://github.com/guillaumelobet/impact_cv/issues" target="_blank">just get in touch</a>
  </br></br>
    <hr>
  </br>
    
    So far, <b><?php echo $count?></b> profiles have been converted<br><br>

    Note: this site is not optimised for speed so, depending on the size of your profile, the conversion could take some time... If it is too large, it might not even work. I'am working on that.




	<div class="right-image">	
      <?php
   	 	if($page->hasImages()){
			foreach($page->images()->shuffle()->limit(1) as $image){
	    		// echo thumb($image, array('width' => 400, 'height' => 350, 'crop' => true));
          		echo '<img src='.$image->url().' height="50%"/>';	      
			}
		}	
  	?>
  	</div>

	
</article>

</section>




<?php snippet('footer') ?>