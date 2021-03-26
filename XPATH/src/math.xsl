<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> 
<xsl:template match="/root"> 
<html> 
 <body> 
   <h3>The MATH courses taught in Reed College are: </h3> 
   <table border="1"> 
    <tr> 
        <th>Titles of the courses</th> 
    </tr> 
    <xsl:for-each select="course[subj='MATH']"> 
    <tr> 
        <td><xsl:value-of select="title"/></td> 
    </tr> 
    </xsl:for-each> 
    </table> 
 </body> 
</html> 
</xsl:template> 
</xsl:stylesheet> 