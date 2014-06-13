#!/usr/bin/perl

use strict;
#use warnings;
use JSON qw( decode_json );
use IO::All;
use Try::Tiny;
use XML::Simple;


# This is not perfect, might raise security issues, but needed to retrieve the file from remote
BEGIN {
 $ENV{'PERL_LWP_SSL_VERIFY_HOSTNAME'} = 0 
};

# Get the JSON file from ImpactStory profile. By doing so, the profile stays up to date
my $json < io->http("https://impactstory.org/user/GuillaumeLobet");
 
 
 
# Decode the JSON file
my $decoded = decode_json($json);
 
# Initialise the bib file
open (MYFILE, '>impactbib.bib');

# Get all the products from the JSON file 
my @products = @{ $decoded->{'products'} };
my $key = 1;
my $key2 = 1;
my $key3 = 1;

# Article variables

my $vol = "";
my $issue = "";
my $pp = "";

foreach my $p ( @products ) {	
	# Print the articles
	if($p->{'biblio'}{'genre'} eq 'article'){
		
		# Initialize the values
		$vol = "";
		$issue = "";
		$pp = "";
		
		# Get the missing inforation on pubmed
		try {
			my $pmid = $p->{'aliases'}{'pmid'}[0];

			# Get the XML file from NCBI
			my $xmlfile < io->http("http://www.ncbi.nlm.nih.gov/pubmed/" . $pmid . "?report=xml");

			# Parse the XML file
			my $xml = new XML::Simple;
  			my $art = $xml->XMLin($xml->XMLin($xmlfile));

			# access XML data
			$vol = $art->{"MedlineCitation"}{"Article"}{"Journal"}{"JournalIssue"}{"Volume"};
			$issue = $art->{"MedlineCitation"}{"Article"}{"Journal"}{"JournalIssue"}{"Issue"};
			$pp = $art->{"MedlineCitation"}{"Article"}{"Pagination"}{"MedlinePgn"};


		} catch {};


		# Get the general informations
		print MYFILE '@article{article' . $key . ", \n";
		
		# for the authors, change "," by "and"
		my $auth = $p->{'biblio'}{'authors'};
		my $find = ",";
		my $replace = " and ";
		$find = quotemeta $find; # escape regex metachars if present
		$auth =~ s/$find/$replace/g;


		print MYFILE 'author = {' . $auth . "}, \n";
  		print MYFILE 'title = {{' . $p->{'biblio'}{'title'} . "}}, \n";
  		print MYFILE 'journal = {' . $p->{'biblio'}{'journal'} . "}, \n";
  		print MYFILE 'volume = {' . $vol . ":" . $issue . "}, \n";
  		print MYFILE 'pages = {' . $pp . "}, \n";
  		print MYFILE 'year = {' . $p->{'biblio'}{'year'} . "}, \n";
  		print MYFILE 'keywords = {article} ';
		
		# Get the metrics
		my @metrics = @{ $p->{'metrics'} };
		foreach my $m ( @metrics ) {	

			# Scopus citations
			if($m->{'display_provider'} eq 'Scopus'){
  				print MYFILE ",\ncitations = {" . $m->{'display_count'} . "}"; 
  			}

			# Mendeley metrics
			if($m->{'display_provider'} eq 'Mendeley'){

				# Readers
				if($m->{'interaction'} eq 'readers'){
  					print MYFILE ",\nreaders = {" . $m->{'display_count'} . "}"; 
  				}
  			}  			

  		}
  		print MYFILE "} \n \n";
		$key = $key + 1;	
 	}

	# Print the presentations (from figshare)
	if($p->{'biblio'}{'genre'} eq 'slides' && $p->{'biblio'}{'repository'} eq 'figshare'){

		# Get the general informations
		print MYFILE '@inproceedings{presentation' . $key2 . ", \n";
  		print MYFILE 'title = {{' . $p->{'biblio'}{'title'} . "}}, \n";
  		print MYFILE "booktitle = {figshare}, \n";
  		print MYFILE 'year = {' . $p->{'biblio'}{'year'} . "}, \n";
  		print MYFILE 'keywords = {presentation} ';
		
		# Get the metrics
		my @metrics = @{ $p->{'metrics'} };
		foreach my $m ( @metrics ) {	

			# Figshare metrics
			if($m->{'display_provider'} eq 'Figshare'){
  				
  				# Figshare views
  				if($m->{'interaction'} eq 'views'){
					print MYFILE ",\nviews = {" . $m->{'display_count'} . "}"; 
				}
				# Figshare shares
  				if($m->{'interaction'} eq 'shares'){
					print MYFILE ",\nshares = {" . $m->{'display_count'} . "}"; 
				}
  			}		

  		}
  		print MYFILE "} \n \n";
		$key2 = $key2 + 1;	
 	}

	# Print the posters (from figshare)
	if($p->{'biblio'}{'genre'} eq 'poster' && $p->{'biblio'}{'repository'} eq 'figshare'){

		# Get the general informations
		print MYFILE '@inproceedings{poster' . $key3 . ", \n";
  		print MYFILE 'title = {{' . $p->{'biblio'}{'title'} . "}}, \n";
  		print MYFILE "booktitle = {figshare}, \n";
  		print MYFILE 'year = {' . $p->{'biblio'}{'year'} . "}, \n";
  		print MYFILE 'keywords = {poster} ';
		
		# Get the metrics
		my @metrics = @{ $p->{'metrics'} };
		foreach my $m ( @metrics ) {	

			# Figshare metrics
			if($m->{'display_provider'} eq 'Figshare'){
  				
  				# Figshare views
  				if($m->{'interaction'} eq 'views'){
					print MYFILE ",\nviews = {" . $m->{'display_count'} . "}"; 
				}
				# Figshare shares
  				if($m->{'interaction'} eq 'shares'){
					print MYFILE ",\nshares = {" . $m->{'display_count'} . "}"; 
				}
  			}		

  		}
  		print MYFILE "} \n \n";
		$key3 = $key3 + 1;	
 	}


}


# close the bib file
close (MYFILE);

print 'DONE';