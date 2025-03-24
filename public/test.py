# import os
# import win32com.client

# def add_hyperlink_to_word(word_file_path, output_file_path, hyperlink_text, hyperlink_url):
#     word = win32com.client.Dispatch('Word.Application')
#     word.Visible = False
#     doc = word.Documents.Open(word_file_path)
#     selection = word.Selection

#     # Go to the end of the document
#     selection.EndKey(Unit=6)
    
#     # Add a new paragraph
#     selection.TypeParagraph()
    
#     # Add the hyperlink
#     selection.Hyperlinks.Add(Anchor=selection.Range, Address=hyperlink_url, TextToDisplay=hyperlink_text)

#     # Save the modified document
#     doc.SaveAs(output_file_path)
#     doc.Close(False)
#     word.Quit()

# def convert_word_to_pdf(word_file_path, pdf_file_path):
#     word = win32com.client.Dispatch('Word.Application')
#     word.Visible = False
#     doc = word.Documents.Open(word_file_path)
#     doc.SaveAs(pdf_file_path, FileFormat=17)
#     doc.Close(False)
#     word.Quit()

# # Use the functions
# current_folder = os.getcwd()

# word_file_path = current_folder+'/document.docx'
# word_file_path_with_hyperlink = current_folder+'/test-file-1.docx'
# pdf_file_path = current_folder+'/test-file-1.pdf'

# add_hyperlink_to_word(word_file_path, word_file_path_with_hyperlink, "Google", "http://www.google.com")
# convert_word_to_pdf(word_file_path_with_hyperlink, pdf_file_path)

import os
import win32com.client
import re

# Initialize Word Application
word = win32com.client.Dispatch("Word.Application")
word.Visible = False

# Open the document
current_folder = os.getcwd()
file_path = current_folder + '/document.docx'
print(file_path)
doc = word.Documents.Open( file_path )

# Define the old string and new hyperlink
start_string = '//'
end_string = '//'
new_string = 'new text'  # The display text of the hyperlink
hyperlink_url = 'http://www.google.com'

# Find and replace the old string with a hyperlink
for paragraph in doc.Paragraphs:
    text = paragraph.Range.Text
    matches = re.findall(f'{start_string}.*?{end_string}', text)
    for match in matches:
        # find = paragraph.Range.Find
        # # print(word.Selection.Range.Text)
        # find.Execute(FindText=match)
        # word.Selection.Range.Text = new_string
        # word.Selection.Hyperlinks.Add(Anchor=word.Selection.Range, Address=hyperlink_url)
        paragraph.Range.Hyperlinks.Add(Anchor=paragraph.Range, Address=hyperlink_url)

# Save as a new .docx file
doc.SaveAs(current_folder + '/test-file-3.docx')

# Save as PDF
doc.SaveAs(current_folder + '/test-file-3.pdf', FileFormat=17)

# Close the document
doc.Close()

# Quit Word
word.Quit()

print("Finished processing")
