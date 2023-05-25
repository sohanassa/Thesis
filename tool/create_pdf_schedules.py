import os
import random
from reportlab.lib import colors
from reportlab.lib.pagesizes import letter, landscape, portrait
from reportlab.lib.units import inch
from reportlab.lib.styles import getSampleStyleSheet
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer


def addColor(color_index, index, color):
    table_style.add('BACKGROUND', (index + 1, color_index), (index + 1, color_index), color)

def getColor():
    color = colors.Color(random.uniform(0.5, 1), random.uniform(0.5, 1), random.uniform(0.5, 1))
    return color

with open("data/num_of_instructors.txt", 'r') as f:
   num_of_instructors = int(f.readline())
f.close()

for k in range (1, num_of_instructors):
    filename = "data/split_input/" + str(k) + ".txt"
    data = []

    table_style = TableStyle([
    ('BACKGROUND', (0, 0), (-1, 0), colors.black),
    ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
    ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
    ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
    ('FONTSIZE', (0, 0), (-1, 0), 10),
    ('BOTTOMPADDING', (0, 0), (-1, -1), 2),
    ('BACKGROUND', (0, 1), (-1, -1), colors.white),
    ('GRID', (0, 0), (-1, -1), 1, colors.black),
    ])
    
    instr_name = ""
    data_s = []
    data_w = []
    instructors_nums = {}
    with open("data/instructors.txt", 'r') as file:
        for line in file:
            parts = line.strip().split('\t')
            if len(parts) == 2:
                instructors_nums[parts[0]] = parts[1]
    with open(filename, 'r') as file:
        for line in file:
            if line.strip() == "WEDNESDAY SCHEDULE:":
                if not line.startswith("Instructor:"):
                    data_s = data_w.copy()
                    data_w = []
            else:
                if not line.startswith("INSTRUCTORS SCHEDULE:"):
                    if line.startswith("Instructor:"):
                        instructor_num = line.strip().split(' ')[1]
                        instr_name = instructors_nums.get(instructor_num,instructor_num)
                        output_file = "data/schedules/schedule_" + instr_name + ".pdf"
                    else:
                        data_w.append(line.strip())

    data.append(['Time', ' Monday ', ' Tuesday ', 'Wednesday', 'Thursday', '   Friday  '])
    class_codes = {}
    with open("data/courses.txt", 'r') as file:
        for line in file:
            parts = line.strip().split('\t')
            if len(parts) == 2:
                class_codes[parts[0]] = parts[1]

    for i in range(len(data_s)):
        week = data_s[i].strip().split(',')
        for j in range(0, len(week)):
            if week[j].isdigit():
                week[j] = class_codes.get(week[j], week[j])
        data_s[i] = ','.join(week)

    for i in range(len(data_w)):
        week = data_w[i]
        if week.isdigit():
            week = class_codes.get(week, week)
        data_w[i] = week

    j = 0
    set = 0
    color_index = 1
    for i in range(9, 21):
        set = 0
        if (i == 9):
            j=0
        if (i == 10):
            j=0
        if (i == 11):
            j=1
        if (i == 12):
            j=2
        if (i == 13):
            j=2
        if (i == 14):
            j=3
        if (i == 15):
            j=4
        if (i == 16):
            j=4
        if (i == 17):
            j=5
        if (i == 18):
            j=6
        if (i == 19):
            j=6
        if (i == 20):
            j=7
        if 1:
            week = data_s[j].split(',')
            for index, value in enumerate(week):
                    if value == "-":
                        week[index] = " "
                    else:
                        if(i == 11 or i == 14 or i == 17 or i == 20):
                            color = getColor()
                            addColor(color_index, index, color)
                            addColor(color_index-1, index, color)
                            addColor(color_index+1, index, color)
                        if (i == 9 or i == 12 or i == 15 or i == 18):
                            color = getColor()
                            addColor(color_index + 1, index, color)
                            addColor(color_index, index, color)
                            addColor(color_index + 2, index, color)
            if (data_w[i-9] != '-'):
                if (i == 9 or i == 12 or i == 15 or i == 18):
                    color = getColor()
                    data.append(['{}:00'.format(i), '', '',data_w[i-9], '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
                    data.append(['{}:30'.format(i), week[0], week[1],'' , week[3], week[4]])
                    addColor(color_index, 2, color)
                    color_index += 1
                if(i == 11 or i == 14 or i == 17 or i == 20):
                    color = getColor()
                    addColor(color_index, 2, color)
                    data.append(['{}:00'.format(i), week[0], week[1], data_w[i-9], week[3], week[4]])
                    addColor(color_index, 2, color)
                    color_index += 1
                    data.append(['{}:30'.format(i), '', '','', '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
                if (i == 10 or i == 13 or i == 16 or i == 19):
                    color = getColor()
                    data.append(['{}:00'.format(i), '', '', data_w[i-9], '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
                    data.append(['{}:30'.format(i), '', '','', '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
            else:
                if (i == 9 or i == 12 or i == 15 or i == 18):
                    data.append(['{}:00'.format(i), '', '','', '', ''])
                    color_index += 1
                    data.append(['{}:30'.format(i), week[0], week[1], week[2], week[3], week[4]])
                    color_index += 1
                if(i == 11 or i == 14 or i == 17 or i == 20):
                    data.append(['{}:00'.format(i), week[0], week[1], week[2], week[3], week[4]])
                    color_index += 1
                    data.append(['{}:30'.format(i), '', '','', '', ''])
                    color_index += 1
                if (i == 10 or i == 13 or i == 16 or i == 19):
                    data.append(['{}:00'.format(i), '', '','', '', ''])
                    color_index += 1
                    data.append(['{}:30'.format(i), '', '','', '', ''])
                    color_index += 1


    table = Table(data)
    table.setStyle(table_style)
    para_text = "Instructor: " + instr_name
    para_style = getSampleStyleSheet()["Normal"]
    para_style.fontSize = 12
    para_style.fontName = "Helvetica-Bold"
    para_style.leftIndent = 63
    para = Paragraph(para_text, para_style)
    doc = SimpleDocTemplate(output_file, pagesize=portrait(letter))
    story = [Paragraph(para_text, para_style), Spacer(1, 10), table]
    doc.build(story)
