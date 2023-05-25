with open("data/result.txt", "r") as f:
    lines = f.readlines()

current_instructor = None
started = 0
cnt = 0
flag1 = 0
flag2 = 0
first_write = 0
num_of_instructors = 0
char = ''
for line in lines:
    if line.startswith("INSTRUCTORS SCHEDULE:"):
        flag1 = 1
        continue
    if line.startswith("Instructor: ") and flag1 == 1:
        num_of_instructors += 1
        started = 1
        current_instructor = line.split(": ")[1].strip()
        first_write = 1
    if started == 1 and flag1 == 1:
        if first_write == 1:
            char = 'w'
        else:
            char = 'a'
        with open(f"data/split_input/{current_instructor}.txt", char) as outfile:
            if char == 'w':
                outfile.write("INSTRUCTORS SCHEDULE:\n")
                first_write = 0
            outfile.write(line)
            cnt += 1
            if cnt == 10:
                started = 0
                cnt = 0
                outfile.write("WEDNESDAY SCHEDULE:\n")
            continue
    outfile.close()
    if line.startswith("WEDNESDAY SCHEDULE:"):
        flag2 = 1
        flag1 = 0
        cnt = 0
        continue
    if line.startswith("Instructor: ") and flag2 == 1:
        started = 1
        current_instructor = line.split(": ")[1].strip()
    if started == 1 and flag2 == 1:
        with open(f"data/split_input/{current_instructor}.txt", "a") as outfile:
            outfile.write(line)
            cnt += 1
            if cnt == 14:
                started = 0
                flag1 = 0
                cnt = 0
            continue

with open(f"data/num_of_instructors.txt", 'w') as outfile:
    outfile.write(str(num_of_instructors))
