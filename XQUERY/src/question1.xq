for $x in doc("http://aiweb.cs.washington.edu/research/projects/xmltk/xmldata/data/courses/reed.xml")//course

where $x/subj="MATH" and $x/place/building ="LIB" and $x/place/room ="204"

return 

<course>
{
 $x/title,
 $x/instructor,
 $x/time
}
</course>