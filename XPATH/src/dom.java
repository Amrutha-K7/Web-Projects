
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;

public class dom {

	public static void main(String[] args) throws Exception {
	
		DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
		
			DocumentBuilder builder = factory.newDocumentBuilder();
			Document doc = builder.parse("reed.xml");
			
			NodeList coursesList = doc.getElementsByTagName("course");
			
			System.out.println("\n"+"-------------------------------------------------------");
			System.out.println("The MATH courses taught in LIB204 class are: ");
			System.out.println("-------------------------------------------------------");
			
			for(int i =0; i<coursesList.getLength();i++)
			{
				
				Node c = coursesList.item(i);
				if(c.getNodeType()== Node.ELEMENT_NODE) 
				{
				
					Element course = (Element)c;
					
					if(course.getElementsByTagName("subj").item(0).getTextContent().equals("MATH") && course.getElementsByTagName("place").item(0).getTextContent().equals("LIB204"))
					{
						String title = course.getElementsByTagName("title").item(0).getTextContent();	
						System.out.println("Title:          "+title);
					}
				}
				
			}
			
		
	}

}
