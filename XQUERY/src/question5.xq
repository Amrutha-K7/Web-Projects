for $d in 
distinct-values(doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course/instructor)

let $courses:= doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course[instructor = $d]


let $instructor:= concat(codepoints-to-string(10),'  Name: ',$d,codepoints-to-string(10))
let $heading := concat('courses taught: ',codepoints-to-string(10))

return 
<instructor>
{
$instructor,
$heading,
for $i in distinct-values($courses/title)
let $coursetitles:= concat($i,codepoints-to-string(10))
         return $coursetitles

}
</instructor>