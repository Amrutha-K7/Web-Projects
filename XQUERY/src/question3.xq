for $d in 
distinct-values(doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course/subj)

let $courses:= doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course[subj = $d]

let $count := count(distinct-values($courses/title))
let $subj:= concat(codepoints-to-string(10),'Department: ',$d,codepoints-to-string(10))
let $numcourses:= concat('No of courses offered: ',$count,codepoints-to-string(10))

return 
{
$subj,
$numcourses
}