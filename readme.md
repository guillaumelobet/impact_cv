# Impact curriculum

## Overview

The aim of the porject is to automaticlly generate a curriculum using LaTeX and the information contained on an ImpactStory profile (altmetrics).

So far, there is three main files to generate the curriculum:

- Perl
	- parse_impactstory_bib.pl
- LaTeX
	- friggeri-cv.cls
	- curriculum.tex
	
To generate the curriculum, run the Perl file, then run the LaTeX one.


## Perl script

To run the Perl file, you need to installt he following packages:

- JSON qw( decode_json )
- IO::All
- Try::Tiny
- XML::Simple

To install them, run in the terminal:

	cpan JSON qw( decode_json )
	cpan IO::All
	cpan Try::Tiny
	cpan XML::Simple
	
Before running the Perl file, do not forget to edit you profile link (line 17). 

The Perl script create a bib file (impactbib.bib), used by LaTeX to generate the CV.


## LaTeX script

The LaTeX script is based on the CV template from Adrien Friggeri (adrien@friggeri.net), https://github.com/afriggeri/CV.

You need to used XeLaTeX and biber to make it run smoothly.



