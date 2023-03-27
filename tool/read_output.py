import os
from reportlab.lib.pagesizes import letter
from reportlab.lib.units import inch
from reportlab.pdfgen import canvas

filename = 'data/result.txt'
output_file = 'schedule_tables.pdf'

data = []
data_w = []

with open(filename, 'r') as file:
    for line in file:
        if line.strip() == "WEDNESDAY SCHEDULE:":
            # Save the current data in data_w and reset data
            data = data_w.copy()
            data_w = []
        else:
            # Append the line to data
            data_w.append(line.strip())

# After the loop is done, save the last set of data in data_w
#data = data_w.copy()
c = canvas.Canvas(output_file, pagesize=letter)

x_offset = 0.5 * inch
y_offset = 10.5 * inch
line_height = 0.25 * inch

c.drawString(x_offset, y_offset, "Instructors Schedule:")
y_offset -= line_height
for i in range(len(data)):
    if data[i].startswith('INSTRUCTOR'):
        schedule_data = []
        i += 1
        while i < len(data) and not data[i].startswith('INSTRUCTOR'):
            schedule_data.append(data[i]) 
            i += 1
        cnt = 0
        with open('data/instructors.txt', 'r') as f1:
            with open('data/courses.txt', 'r') as f2:
                for j, row in enumerate(schedule_data):
                    cols = row.split(',')
                    # wed_cnt = 0
                    for col in cols:
                        if row.startswith("Instructor:"):
                            if cnt == 3:
                                c.showPage()
                                y_offset = 10.5 * inch
                                x_offset = 0.5 * inch
                                cnt = 0
                            instrs = row.split(': ')
                            inst_num = instrs[1]
                            f1.seek(0)
                            for line in f1:
                                parts = line.strip().split('\t')
                                if len(parts) >= 2 and parts[0] == inst_num:
                                    col = parts[1]
                                    break
                            c.drawString(x_offset, y_offset, "Instructor:")
                            x_offset += 0.8 * inch
                            #c.line(x_offset, y_offset - line_height * 2, x_offset  * inch, y_offset - line_height)
                            c.drawString(x_offset, y_offset, col)
                            x_offset -= 0.8 * inch
                            y_offset -= line_height
                            c.drawString(x_offset, y_offset, "Monday Tuesday Wednesday Thursday Friday")
                            x_offset += 0.75 * inch
                            cnt = cnt + 1
                        else:   
                            if col !='-':
                                f2.seek(0)
                                for line in f2:
                                    parts = line.strip().split('\t')
                                    if len(parts) >= 2 and parts[0] == col:
                                        col = parts[1]
                                        break
                                #c.line(x_offset, y_offset - line_height * 2, x_offset  * inch, y_offset - line_height)
                                # if wed_cnt == 2:
                                #     c.drawString(x_offset, y_offset, data_w[j])
                                # else: 
                                c.drawString(x_offset, y_offset, col)
                                # wed_cnt +=1
                                x_offset += 0.75 * inch
                            else: 
                                #c.line(x_offset, y_offset - line_height * 2, x_offset  * inch, y_offset - line_height)
                                # if wed_cnt == 2:
                                #      c.drawString(x_offset, y_offset, data_w[j])
                                # else: 
                                c.drawString(x_offset, y_offset, col)
                                # wed_cnt +=1

                                x_offset += 0.75 * inch
                    y_offset -= line_height
                    x_offset = 0.5 * inch
                    #j+=4
                y_offset -= line_height

        

c.save()
#print(data_w[2])
os.system(f'open {output_file}')

