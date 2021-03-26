for $d in distinct-values(doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course/title)
let $courses:= doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course[title = $d]
return 
<course>
<title>{$d}</title>
{
         for $i in distinct-values($courses/instructor)
         return <instructor> {$i} </instructor>
}</course>