for $d in 
distinct-values(doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course/instructor)

let $courses:= doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course[instructor = $d]

let $count := count(distinct-values($courses/title))
let $subj:= concat(codepoints-to-string(10),'  Name: ',$d,codepoints-to-string(10))
let $numcourses:= concat('No of courses taught: ',$count,codepoints-to-string(10))

return 
<instructor>
{
$subj,
$numcourses
}
</instructor>