
import javax.xml.xpath.*;
import org.w3c.dom.*;
import org.xml.sax.InputSource;


public class xpath {

	static String instructorG;
	static String titlesW="";
	public static void main(String[] args) throws Exception
	{
			eval("//course","reed.xml");
		
	}
	
	
	static void eval( String query, String document ) throws Exception 
	{
		XPath xpath = XPathFactory.newInstance().newXPath();
		InputSource inputSource = new InputSource(document);
		NodeList courseList = (NodeList) xpath.evaluate(query,inputSource,XPathConstants.NODESET);
		
		
		for (int i = 0; i < courseList.getLength(); i++)   
		{ 
			Node c = courseList.item(i);
			
			String subject= xpath.compile(".//subj").evaluate(c);
			String building= xpath.compile(".//place/building").evaluate(c);
			String room= xpath.compile(".//place/room").evaluate(c);
			String crse= xpath.compile(".//crse").evaluate(c);
			String place = building+room;
			String instructor = xpath.compile(".//instructor").evaluate(c);
			String title = xpath.compile(".//title").evaluate(c);
			
			
			if(i==0)
			{
				System.out.println("\n"+"-------------------------------------------------------");
				System.out.println("The MATH courses taught in LIB204 class are: ");
				System.out.println("-------------------------------------------------------");
			}
			
			if(subject.equals("MATH") && place.equals("LIB204"))
			{
				
				System.out.println("Title:   "+title);
				
			}	
			
			if(subject.equals("MATH") && crse.equals("412"))
			{
				instructorG=instructor;
			}
			if(instructor.equalsIgnoreCase("Wieting"))
			{
				String titlesw = "Title: "+title+"\n";
				titlesW += titlesw;
			}
			
			
		}
		
		System.out.println("\n"+"-------------------------------------------------------");
		System.out.println("Instructor who teaches MATH 412 is : "+instructorG);
		System.out.println("-------------------------------------------------------"+"\n");
		
		System.out.println("------------------------------------------------------------");
		System.out.println("Titles of all the courses taught by instructor Wieting are : ");
		System.out.println("------------------------------------------------------------");
		System.out.println(titlesW);
	}
		
}

