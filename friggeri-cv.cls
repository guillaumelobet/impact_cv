%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
% Friggeri Resume/CV
% XeLaTeX Template
% Version 1.0 (5/5/13)
%
% This template has been downloaded from:
% http://www.LaTeXTemplates.com
%
% Original author:
% Adrien Friggeri (adrien@friggeri.net)
% https://github.com/afriggeri/CV
%
% License:
% CC BY-NC-SA 3.0 (http://creativecommons.org/licenses/by-nc-sa/3.0/)
%
% Important notes:
% This template needs to be compiled with XeLaTeX and the bibliography, if used,
% needs to be compiled with biber rather than bibtex.
%
% Modified by Guillaume Lobet, Université de Liège
% 20140613
%
%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%


\ProvidesClass{friggeri-cv}[2012/04/30 CV class]
\NeedsTeXFormat{LaTeX2e}

\DeclareOption{print}{\def\@cv@print{}}
\DeclareOption*{%
  \PassOptionsToClass{\CurrentOption}{article}%
}
\ProcessOptions\relax
\LoadClass[a4paper,english,10pt]{article}


\usepackage{adforn}                % Other nice ornaments
\usepackage{pifont}

\usepackage{ulem}

%%%%%%%%%%
% Colors %
%%%%%%%%%%

\RequirePackage{xcolor}

\definecolor{white}{RGB}{255,255,255}

\definecolor{darkgray}{HTML}{050505}
\definecolor{gray}{HTML}{050505}
\definecolor{lightgray}{HTML}{605F60}
\definecolor{lightgray3}{HTML}{e8e8e8}
\definecolor{lightgray2}{HTML}{979797}

\definecolor{green}{HTML}{41A02A}
\definecolor{orange}{HTML}{F08C05}
\definecolor{purple}{HTML}{7C64A0}
\definecolor{red}{HTML}{D40000}
\definecolor{blue}{HTML}{056EE6}
\definecolor{brown}{HTML}{056EE6}
\definecolor{green2}{HTML}{44D39C}
\definecolor{light}{gray}{0.2}


\ifdefined\@cv@print
  \colorlet{green2}{lightgray2}
  \colorlet{green}{lightgray2}
  \colorlet{orange}{lightgray2}
  \colorlet{purple}{lightgray2}
  \colorlet{brown}{lightgray2}
  \colorlet{red}{lightgray2}
  \colorlet{blue}{lightgray2}
  \colorlet{fillheader}{lightgray3}
  \colorlet{header}{gray}
\else
  \colorlet{fillheader}{gray}
  \colorlet{header}{white}
\fi
\colorlet{textcolor}{gray}
\colorlet{headercolor}{gray}

%%%%%%%%%
% Fonts %
%%%%%%%%%

\RequirePackage[quiet]{fontspec}
\RequirePackage[math-style=TeX,vargreek-shape=unicode]{unicode-math}

\newfontfamily\bodyfont[]{Helvetica Neue}
\newfontfamily\thinfont[]{Helvetica Neue UltraLight}
\newfontfamily\headingfont[]{Helvetica Neue Condensed Bold}

\defaultfontfeatures{Mapping=tex-text}
\setmainfont[Mapping=tex-text, Color=textcolor]{Helvetica Neue Light}


%%%%%%%%%%
% Header %
%%%%%%%%%%

\RequirePackage{tikz}

\newcommand{\rolefont}{%
  \fontsize{14pt}{24pt}\selectfont%
  \thinfont%
  \color{white}%
}

\newcommand{\header}[3]{%
   
    \vspace{-1.5cm}
    \rule{\linewidth}{0.1pt}\\

    \begingroup  
        \fontsize{25pt}{50pt}\selectfont
        {\thinfont #1} {\bodyfont #2}
    \endgroup    
    
    \begingroup  
        \fontsize{12pt}{24pt}\selectfont
        #3
    \endgroup 
    
\rule{\linewidth}{0.1pt}\\
}


\newcommand{\descr}[3]{%
  \parbox[t]{\linewidth}{%
    \textbf{#1}%
    \hfill%
    {\footnotesize\addfontfeature{Color=lightgray} #2}\\%
    #3\vspace{\parsep}%
  }\\}
  


%%%%%%%%%%%%%
% Structure %
%%%%%%%%%%%%%
\RequirePackage{parskip}

\newcounter{colorCounter}
\def\@sectioncolor#1#2#3{%
  {%
    \color{%
      \ifcase\value{colorCounter}%
        blue\or%
        red\or%
        orange\or%
        green\or%
        purple\or%
        brown\or%
        green2\or%
        red\or%
        orange\or%
        green\else%
        headercolor\fi%
    } #1#2#3%
  }%
  \stepcounter{colorCounter}%
}

\renewcommand{\section}[1]{
  \par\vspace{\parskip}
  {%
    \LARGE\headingfont\color{headercolor}%
    \@sectioncolor #1%
  }
  \par\vspace{\parskip}
}

\renewcommand{\subsection}[2]{
  \par\vspace{.5\parskip}%
  \Large\headingfont\color{headercolor} #2%
  \par\vspace{.25\parskip}%
}

%\pagestyle{plain}
\usepackage{lastpage}
\usepackage{fancyhdr}

\pagestyle{fancy}
\fancyhf{}
\rfoot{\thepage\ of \pageref{LastPage}} 
\rhead{Guillaume Lobet}
\renewcommand{\headrulewidth}{0pt}%
\fancyhfoffset[L]{-2pt}

\fancypagestyle{first}{
\fancyhf{}
\rfoot{\thepage\ of \pageref{LastPage}}}

%%%%%%%%%%%%%%%%%%%%
% List environment %
%%%%%%%%%%%%%%%%%%%%

\setlength{\tabcolsep}{0pt}
\newenvironment{entrylist}{%
  \begin{tabular*}{\textwidth}{@{\extracolsep{\fill}}ll}
}{%
  \end{tabular*}
}
\renewcommand{\bfseries}{\headingfont\color{headercolor}}
\newcommand{\entry}[4]{%
  #1&\parbox[t]{11cm}{%
    \textbf{#2}%
    \hfill%
    {\footnotesize\addfontfeature{Color=lightgray} #3}\\%
    #4\vspace{\parsep}%
  }\\}
  
  
%%%%%%%%%%%%%%
% Side block %
%%%%%%%%%%%%%%

\RequirePackage[absolute,overlay]{textpos}
\setlength{\TPHorizModule}{1.1cm}
%\setlength{\TPVertModule}{0.383cm}
\setlength{\TPVertModule}{0.65cm}
\newenvironment{aside}{%
  \let\oldsection\section
  \renewcommand{\section}[1]{
    \par\vspace{\baselineskip}{\Large\headingfont\color{headercolor} ##1}
  }
  \begin{textblock}{3.6}(1, 4.33)
  \begin{flushright}
  \obeycr
}{%
  \restorecr
  \end{flushright}
  \end{textblock}
  \let\section\oldsection
}

%%%%%%%%%%%%%%%%
% Bibliography %
%%%%%%%%%%%%%%%%

\RequirePackage[style=verbose, maxnames=99, sorting=ydnt, backend=biber]{biblatex}

\DeclareSourcemap{
    \maps[datatype=bibtex]{
        % Map the citations
        \map{
            \step[fieldsource=citations]
            \step[fieldset=usera,origfieldval]
        }
        % Map the readers
        \map{
            \step[fieldsource=readers]
            \step[fieldset=userb,origfieldval]
        }
        % Map the views
        \map{
            \step[fieldsource=views]
            \step[fieldset=userc,origfieldval]
        }    
        % Map the shares
        \map{
            \step[fieldsource=shares]
            \step[fieldset=userd,origfieldval]
        }                
    }
}

%\AtEveryBibitem{%
%    \csappto{blx@bbx@\thefield{entrytype}}{% put at end of entry
%        \iffieldundef{usera}{%
%        {0}}{%
%        \printfield{usera}
%        }
%    }
%}


\DeclareFieldFormat[article]{title}{#1\par}
\DeclareFieldFormat[inproceedings]{title}{#1\par}

\DeclareBibliographyDriver{article}{%
  \printfield{title}%
  \newblock%
  \printnames{author}%
  \par%
  \newblock%
  {%
    \footnotesize\addfontfeature{Color=lightgray}%
    \usebibmacro{journal+issuetitle}%
    \setunit{\space}%
    \printfield{pages}%
    \newunit%
    \printlist{publisher}%
    \newunit%
  }
  \par%
  \newblock%
  {%
    \footnotesize\addfontfeature{Color=lightgray}%
    Scopus citation(s):
    \printfield{usera}%
    , Mendeley reader(s):
    \printfield{userb}%
  }  
  \par\vspace{0.6\baselineskip}
}



\DeclareBibliographyDriver{inproceedings}{%
  \printfield{title}%
  \newblock%
  \printnames{author}%
  \par%
  \newblock%
  {%
    \footnotesize\addfontfeature{Color=lightgray}%
    \printfield{booktitle}%
    \setunit{\addcomma\space}%
    \printfield{pages}%
    \setunit{\addcomma\space}%
    \printlist{location}%
    \setunit{\addcomma\space}%
    \printfield{year}%
    \newunit%
  }
  \par%
  \newblock%
  {%
    \footnotesize\addfontfeature{Color=lightgray}%
    figshare view(s):
    \printfield{userc}%
    , figshare share(s):
    \printfield{userd}%
  }  
  \par\vspace{0.6\baselineskip}  
}


\DeclareNameFormat{author}{%
  \small\addfontfeature{Color=lightgray}%
  \ifblank{#3}{}{#3\space}#1%
  \ifthenelse{\value{listcount}<\value{liststop}}
    {\addcomma\space}
    {}%
}

\newcommand{\printbibsection}[2]{
  \begin{refsection}
    \nocite{*}
    \printbibliography[sorting=chronological, type={#1}, title={#2}, heading=subbibliography]
  \end{refsection}
}

\DeclareSortingScheme{chronological}{
  \sort[direction=descending]{\field{year}}
  \sort[direction=descending]{\field{month}}
}


%%%%%%%%%%%%%%%%
% Other tweaks %
%%%%%%%%%%%%%%%%

\RequirePackage[
    left=6.1cm,
    top=1cm,
    right=1.8cm,
    bottom=2cm,
    nohead
    ]
    {geometry}
    
\RequirePackage{hyperref}
