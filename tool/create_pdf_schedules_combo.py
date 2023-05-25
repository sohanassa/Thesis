import os
import random
import sys
from reportlab.lib import colors
from reportlab.lib.pagesizes import letter, landscape, portrait
from reportlab.lib.units import inch
from reportlab.lib.styles import getSampleStyleSheet
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer

courses = sys.argv[1].split(",")

unique_courses = list(set([course.split("_")[0] for course in courses]))

instructors = list(set(sys.argv[2].split(",")))
number_of_instructors = len(instructors)


usernames = ['void'] * number_of_instructors
for i in range (0, len(instructors)):
    usernames[i] = instructors[i-1]

def addColor(color_index, index, color):
    table_style.add('BACKGROUND', (index + 1, color_index), (index + 1, color_index), color)

def getColor():
    color = colors.Color(random.uniform(0.5, 1), random.uniform(0.5, 1), random.uniform(0.5, 1))
    return color

for k in range (1, 2):

    data = []

    table_style = TableStyle([
    ('BACKGROUND', (0, 0), (-1, 0), colors.black),
    ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
    ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
    ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
    ('FONTSIZE', (1, 1), (-1, -1), 7),
    ('FONTSIZE', (0, 0), (-1, 0), 10),
    ('FONTSIZE', (0, 0), (0, -1), 10),
    ('BOTTOMPADDING', (0, 0), (-1, -1), 2),
    ('BACKGROUND', (0, 1), (-1, -1), colors.white),
    ('GRID', (0, 0), (-1, -1), 1, colors.black),
    ])
    
    instr_name = ""
    data_array_s = [[] for _ in range(number_of_instructors)]
    data_array_w = [[] for _ in range(number_of_instructors)]
    instructors_nums = {}
    instructors_names = {}
    output_file = "data/schedules/schedule_" + "combo2.pdf"
    with open("data/instructors.txt", 'r') as file:
        for line in file:
            parts = line.strip().split('\t')
            if len(parts) == 2:
                instructors_nums[parts[0]] = parts[1]
                instructors_names[parts[1]] = parts[0]

    instrs_numbers = []
    filenames = []

    for i in range (0, number_of_instructors):
        if(usernames[i] == 'void'):
            filenames.insert(i, "data/split_input/0.txt")
        else:
            temp = instructors_names.get(usernames[i])
            filenames.insert(i, "data/split_input/" + str(temp) + ".txt")
    for j in range (0, number_of_instructors): 
        with open(filenames[j], 'r') as file:
            for line in file:
                if line.strip() == "WEDNESDAY SCHEDULE:":
                    if not line.startswith("Instructor:"):
                        data_array_s[j] = data_array_w[j].copy()
                        data_array_w[j] = []
                else:
                    if not line.startswith("INSTRUCTORS SCHEDULE:"):
                        if line.startswith("Instructor:"):
                            instructor_num = line.strip().split(' ')[1]
                            instr_name = instructors_nums.get(instructor_num,instructor_num)
                        else:
                            data_array_w[j].append(line.strip())
    
    data.append(['Time', ' Monday ', ' Tuesday ', 'Wednesday', 'Thursday', '   Friday  '])
    class_codes = {}
    with open("data/courses.txt", 'r') as file:
        for line in file:
            parts = line.strip().split('\t')
            if len(parts) == 2:
                class_codes[parts[0]] = parts[1]

    for k in range (0, number_of_instructors):
        for i in range(len(data_array_s[k])):
            wk = data_array_s[k][i].strip().split(',')
            for j in range(0, len(wk)):
                if wk[j].isdigit():
                    wk[j] = class_codes.get(wk[j], wk[j])
            data_array_s[k][i] = ','.join(wk)

        for i in range(len(data_array_w[k])):
            wk = data_array_w[k][i]
            if wk.isdigit():
                wk = class_codes.get(wk, wk)
            data_array_w[k][i] = wk

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
            week = [None] * number_of_instructors
            for k in range (0, number_of_instructors):
                week[k] = data_array_s[k][j].split(',')
            for k in range (0, number_of_instructors):
                if data_array_w[k][i-9] == '-':
                    data_array_w[k][i-9] = ''
            for k in range (0, number_of_instructors):
                if data_array_w[k][i-9] not in courses:
                    data_array_w[k][i-9] = ''
            for k in range (0, number_of_instructors):
                for index, value in enumerate(week[k]):
                    if value == "-":
                        week[k][index] = ""
                    if value not in courses:
                        week[k][index] = ""
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
            
            wed = "".join([l[i-9] for l in data_array_w])
            mo = "".join([l[0] for l in week])
            tu = "".join([l[1] for l in week])
            wed2 = "".join([l[2] for l in week])
            thu = "".join([l[3] for l in week])
            fr = "".join([l[4] for l in week])
            if any(data[i-9] for data in data_array_w):
                if (i == 9 or i == 12 or i == 15 or i == 18):
                    color = getColor()
                    data.append(['{}:00'.format(i), '', '',wed, '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
                    data.append(['{}:30'.format(i), mo, tu, wed2, thu, fr])
                    addColor(color_index, 2, color)
                    color_index += 1
                if(i == 11 or i == 14 or i == 17 or i == 20):
                    color = getColor()
                    addColor(color_index, 2, color)
                    data.append(['{}:00'.format(i), mo, tu, wed + " " + wed2, thu, fr])

                    addColor(color_index, 2, color)
                    color_index += 1
                    data.append(['{}:30'.format(i), '', '','', '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
                if (i == 10 or i == 13 or i == 16 or i == 19):
                    color = getColor()
                    data.append(['{}:00'.format(i), '', '', wed, '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
                    data.append(['{}:30'.format(i), '', '','', '', ''])
                    addColor(color_index, 2, color)
                    color_index += 1
            else:
                if (i == 9 or i == 12 or i == 15 or i == 18):
                    data.append(['{}:00'.format(i), '', '','', '', ''])
                    color_index += 1
                    data.append(['{}:30'.format(i), mo, tu, wed2, thu, fr])
                    color_index += 1
                if(i == 11 or i == 14 or i == 17 or i == 20):
                    data.append(['{}:00'.format(i), mo, tu, wed2, thu, fr])
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
    para_text = "Courses: " + ', '.join(unique_courses)
    para_style = getSampleStyleSheet()["Normal"]
    para_style.fontSize = 9
    para_style.fontName = "Helvetica-Bold"
    para_style.leftIndent = 63
    para = Paragraph(para_text, para_style)
    doc = SimpleDocTemplate(output_file, pagesize=portrait(letter))
    story = [Paragraph(para_text, para_style), Spacer(1, 10), table]
    doc.build(story)
    os.system(f'open {output_file}')