import mysql.connector

# setting up connection parameters
config = {
  'user': 'root',
  'password': '',
  'host': 'localhost',
  'database': 'scheduleMaker'
}

# connecting to database
cnx = mysql.connector.connect(**config)

def readInstructors(cnx):
  cursor = cnx.cursor()

  query = "SELECT * FROM instructors"
  cursor.execute(query)

  with open('data/inputData.dzn', 'w') as f1:
      with open('data/instructors.txt', 'w') as f2:
        num_of_instructors = 0
        for row in cursor.fetchall():
          num_of_instructors += 1
          f2.write(str(num_of_instructors) + "\t" + str(row[0]) + "\n")
        f1.write(f"numOfInstructors = {num_of_instructors};\n\n")
        f1.write("INSTRUCTORS = {")
        for i in range(1, num_of_instructors + 1):
            if i == num_of_instructors:
              f1.write("T" + str(i))
            else:
              f1.write("T" + str(i) + ", ")
        f1.write("};\n")

  f1.close()
  f2.close()
  cursor.close()


def readCourses(cnx):
    cursor = cnx.cursor()

    query = "SELECT * FROM courses"
    cursor.execute(query)

    with open('data/inputData.dzn', 'a') as f1:
        with open('data/courses.txt', 'w') as f2:
            f1.write("\n")
            num_of_courses = 0
            for row in cursor.fetchall():
                num_of_courses += 1
                f2.write(str(num_of_courses) + "\t" + str(row[0]) + "\n")
            f1.write("CLASSES = {E, ")
            for i in range(1, num_of_courses + 1):
                if i == num_of_courses:
                    f1.write("C" + str(i))
                else:
                    f1.write("C" + str(i) + ", ")
            f1.write("};\n")

    cursor.close()
    f1.close()
    f2.close()

def readConflicts(cnx):
    cursor = cnx.cursor()

    query = "SELECT * FROM conflicts"
    cursor.execute(query)

    with open('data/inputData.dzn', 'a') as f1:
        with open('data/courses.txt', 'r') as f2:
            f1.write("\n")
            rows = cursor.fetchall()
            num_of_conflicts = len(rows)
            f1.write(f"numOfConflicts = {num_of_conflicts};\n\n")
            f1.write("DISJUNCT_INPUT = [")
            cnt=0
            for row in rows:
                cnt = cnt + 1
                course_name = str(row[0])
                f2.seek(0)
                for line in f2:
                    parts = line.strip().split('\t')
                    if len(parts) >= 2 and parts[1] == course_name:
                      course_number = parts[0]
                      break
                f1.write("C" + str(course_number) + ", ")
                course_name = str(row[1])
                f2.seek(0)
                for line in f2:
                    parts = line.strip().split('\t')
                    if len(parts) >= 2 and parts[1] == course_name:
                      course_number = parts[0]
                      break
                if cnt == num_of_conflicts:    
                  f1.write("C" + str(course_number))
                else:
                   f1.write("C" + str(course_number) + ", ")
            f1.write("];\n")

    cursor.close()
    f1.close()
    f2.close()

def readParallel(cnx):
    cursor = cnx.cursor()

    query = "SELECT * FROM parallel"
    cursor.execute(query)

    parallel_dict = {}
    with open('data/courses.txt', 'r') as f2:
        for line in f2:
            parts = line.strip().split('\t')
            if len(parts) >= 2:
                parallel_dict[parts[1]] = parts[0]

    with open('data/inputData.dzn', 'a') as f1:
        f1.write("\n")
        rows = cursor.fetchall()
        num_of_parallel = len(rows)
        f1.write(f"numOfParallelLectures = {num_of_parallel};\n\n")
        f1.write("PARALLEL_LEC_INPUT = [")
        for i, row in enumerate(rows):
            course_numbers = []
            for course_name in row:
                course_number = parallel_dict.get(course_name)
                if course_number is not None:
                    course_numbers.append("C" + course_number)
            f1.write(", ".join(course_numbers))
            if i != num_of_parallel - 1:
                f1.write(", ")
        f1.write("];\n\n")

    cursor.close()
    f1.close()
    f2.close()

def readWednesdayBusyInput(cnx):
    hours = list(range(9, 21))

    # create a dictionary to store the busy hours for each instructor
    busy_dict = {}

    # read the list of instructors from the inputTXT.txt file
    with open('data/instructors.txt') as infile:
        # skip the first three lines
        # get the list of instructors in the order specified in the file
        instructors = [line.strip().split('\t')[1] for line in infile]

    # get the list of instructors and their unavailable hours on Wednesdays from the database
    with cnx.cursor() as cursor:
        sql = "SELECT instructor_username, hour FROM instructors_unable_hours_wednesdays"
        cursor.execute(sql)
        results = cursor.fetchall()

        # loop through the results and populate the busy_dict
        for row in results:
            instructor = row[0]
            hour =  int(row[1])
            if instructor not in busy_dict:
                busy_dict[instructor] = [0] * len(hours)
            if hour in hours:
                busy_dict[instructor][hour - 9] = 1

    with open('data/inputData.dzn', 'a') as f:
        # write the opening line of the list
        f.write("W_BUSY_INPUT = [\n")

        for i, instructor in enumerate(instructors):
            busy_hours = busy_dict.get(instructor, [0] * len(hours))

            # loop through the busy hours and write them to the file
            for j, hour in enumerate(busy_hours):
                f.write(f"{hour}")

                # check if this is the last hour for this instructor
                if j == len(busy_hours) - 1:
                    # check if this is the last instructor overall
                    if i == len(instructors) - 1:
                        f.write("];\n\n\n")
                    else:
                        # add an extra empty line after each instructor
                        f.write(",\n\n")
                else:
                    f.write(",\n")
    f.close()

def readBusyInput(cnx):
    hours = [9.0, 10.5, 12.0, 13.5, 15.0, 16.5, 18.0, 19.5]
    days = [1, 2, 3, 4, 5]

    # create a dictionary to store the busy hours for each instructor
    busy_dict = {}

    # read the list of instructors from the inputTXT.txt file
    with open('data/instructors.txt') as infile:
        # skip the first three lines
        # get the list of instructors in the order specified in the file
        instructors = [line.strip().split('\t')[1] for line in infile]

    # get the list of instructors and their unavailable hours from the database
    with cnx.cursor() as cursor:
        sql = "SELECT instructor_username, hour, day FROM instructors_unable_hours"
        cursor.execute(sql)
        results = cursor.fetchall()

        # loop through the results and populate the busy_dict
        for row in results:
            instructor = row[0]
            hour =  float(row[1])
            day = int(row[2])
            if instructor not in busy_dict:
                busy_dict[instructor] = [[0] * len(days) for _ in range(len(hours))]
            if hour in hours and day in days:
                hour_index = hours.index(hour)
                if day == 1:
                    busy_dict[instructor][hour_index][0] = 1
                    busy_dict[instructor][hour_index][3] = 1
                if day == 2:
                    busy_dict[instructor][hour_index][1] = 1
                    busy_dict[instructor][hour_index][4] = 1

    with open('data/inputData.dzn', 'a') as f:
        # write the opening line of the list
        f.write("BUSY_INPUT = [\n")

        for i, instructor in enumerate(instructors):

            # handle case where instructor not in busy_dict
            if instructor not in busy_dict:
                for j in range(len(hours)):
                    for k in range(len(days)):
                        f.write("0")
                        if k == len(days) - 1 and j == len(hours) - 1:
                            # last day and last hour for this instructor
                            if i == len(instructors) - 1:
                                f.write("];\n")
                            else:
                                # add an extra empty line after each instructor
                                f.write(",\n\n")
                        elif k == len(days) - 1:
                            # last day for this hour and this instructor
                            f.write(",\n")
                        else:
                            f.write(", ")
            else:
                busy_hours = busy_dict.get(instructor, [[0] * len(days) for _ in range(len(hours))])
                # loop through the busy hours and write them to the file
                for j, hour in enumerate(busy_hours):
                    for k, day in enumerate(hour):
                        f.write(f"{day}")
                        if k == len(days) - 1 and j == len(busy_hours) - 1:
                            # last day and last hour for this instructor
                            if i == len(instructors) - 1:
                                f.write("];\n")
                            else:
                                # add an extra empty line after each instructor
                                f.write(",\n\n")
                        elif k == len(days) - 1:
                            # last day for this hour and this instructor
                            f.write(",\n")
                        else:
                            f.write(", ")
        #f.write("CLASS_DETAILS_INPUT =  [E,T1,30,1,0,C1,T1,30,1,0,C2,T2,70,1,0,C3,T3,30,1,0,C4,T3,30,2,1,C5,T3,15,2,1,C6,T3,30,1,0,C7,T3,30,1,0,C8,T3,30,4,2]")
        f.close()

def readClassDetails(cnx):
    cursor = cnx.cursor()
    query = "SELECT * FROM courses"
    cursor.execute(query) 
    courses_dic = {}
    instructors_dic = {}
    with open('data/courses.txt', 'r') as f1:
        for line in f1:
            parts = line.strip().split('\t')
            if len(parts) >= 2:
                courses_dic[parts[1]] = parts[0]

    with open('data/instructors.txt', 'r') as f2:
        for line in f2:
            parts = line.strip().split('\t')
            if len(parts) >= 2:
                instructors_dic[parts[1]] = parts[0]

    with open('data/inputData.dzn', 'a') as f:
        f.write("\nCLASS_DETAILS_INPUT =  [\nE, T1, 30, 1, 0,\n")
        results = cursor.fetchall()
        num_rows = len(results)
        for i, row in enumerate(results):
            course_number = courses_dic.get(row[0])
            type = row[1]
            max_students = int(row[2])
            #days_preference = int(row[3])
            instr_number = instructors_dic.get(row[4])
            f.write("C" + str(course_number) + ", T" + str(instr_number) + ", " + str(max_students) + ", " + str(type) + ", 0")
            if i == num_rows - 1:
                f.write("]\n\n")
            else:
                f.write(",\n")


readInstructors(cnx)
readCourses(cnx)
readConflicts(cnx)
readParallel(cnx)
readWednesdayBusyInput(cnx)
readBusyInput(cnx)
readClassDetails(cnx)


cnx.close()

