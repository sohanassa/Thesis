enum DAYS = {MO, TU, WE, TH, FR};
int: numOfSlots = 8;
set of int: SLOTS = 1..numOfSlots;
%WILL BE CLASSES INPUT
enum CLASSES = {EMPTY,C1,C2,C3,C4,C5,C6,C7,C8,C9,C10,C11,C12,C13,C14,C15,C16,C17,C18,C19,C20,C21,C22,C23,C24,C25,C26,C27,C28,C29,C30};
int: numOfClasses = length(CLASSES) - 1;
%WILL BE INSTRUCTORS INPUT
enum INSTRUCTORS = {T1,T2,T3,T4,T5,T6,T7,T8,T9,T10};

%WILL BE ROOMS INPUT
int: numOfRooms = 8;
set of int: roomsSet = 1..numOfRooms;
enum ROOMS = {R1,R2,R3,R4,R5,R6,R7,R8};
%ROOMS AND THEIR CAPACITY AND TYPE
%1 FOR LECTURE ROOM AND 2 FOR LAB
array [roomsSet, 1..3] of int: ROOM_DETAILS = [| R1,20,1 | R2,80,1 | R3,40,1 |R4,40,2| R5,80,1 | R6,80,1 | R7,80,1 | R8,80,1 |];

%INPUT FOR NUMBER OF CLASS CONFLICTS
int: numOfConflicts;
set of int: conflictsSet = 1..numOfConflicts;

%array[INSTRUCTORS, DAYS, SLOTS] of int: BUSY;
%INPUT FOR CLASS CONFLICTS
array[conflictsSet, 1..2] of CLASSES: DISJUNCT;
%WILL BE INPUT FOR LINKING CLASSES TO INSTRUCTORS
%CLASS NUMBER, INSTRUCTOR NUMBER, NUMBER OF STUDENTS, TYPE: 1 FOR LECTURE, 2 FOR LAB
array[CLASSES, 1..4] of int: CLASS_DETAILS = [|EMPTY,0,0,1 | C1,T1,30,1 | C2,T2,70,1 | C3,T2,30,1 | C4,T2,30,2| C5, T3,15,1 | C6, T1,30,1| C7, T3,30,1| C8, T3,30,1| C9, T3,30,1| C10, T1, 30, 3|C11, T3,30,1|C12, T4,30,1|C13, T5,30,1|C14, T6,30,1|C15, T7,30,1|C16, T8,30,1|C17, T9,30,1|C18, T10,30,1|C19, T4,30,1|C20, T5,30,1|C21, T6,30,1|C22, T7,30,1|C23, T8,30,1| C24, T10,30,1|C25, T4,30,1|C26, T5,30,1|C27, T6,30,1|C28, T7,30,1|C29, T8,30,1|C30, T9,30,1|];
%THE INSTRUCTORS SCHEDULE OUTPUT
array[INSTRUCTORS, DAYS, SLOTS] of var CLASSES: I_SCHEDULE;
%THE ROOMS SCHEDULE OUTPUT
array[ROOMS, DAYS, SLOTS] of var CLASSES: R_SCHEDULE;

%EACH CLASS CAN ONLY BE TAOUGHT BY ITS INSTRUCTOR
constraint forall(c in CLASSES where c != EMPTY)(
              forall(i in INSTRUCTORS, d in DAYS, s in SLOTS where i != CLASS_DETAILS[c,2])
                  (I_SCHEDULE[i,d,s] != CLASSES[c]));

%LECTURES COLFLICTS  
constraint forall(f in conflictsSet)(
             forall(i in INSTRUCTORS, d in DAYS, s in SLOTS)(
               forall(i2 in INSTRUCTORS, d2 in DAYS, s2 in SLOTS where d==d2 /\ s==s2 /\ I_SCHEDULE[i,d,s] == DISJUNCT[f,1])(
                (I_SCHEDULE[i2,d2,s2] != DISJUNCT[f,2])
                )));
                
%LECTURES ON MO-TH, TU-FR              
constraint forall(c in CLASSES where c != EMPTY /\ CLASS_DETAILS[c,4] == 1)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS where d == MO \/ d == TH)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 2
               \/ sum (i in INSTRUCTORS, d in DAYS, s in SLOTS where d == TU \/ d == FR)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 2);

%LECTURES BE TAUGHT AT THE SAME TIME SLOT ON DIFFERENT DAYS (MO-TH)
constraint forall(i in INSTRUCTORS, s in SLOTS)(
               forall(s2 in SLOTS where s != s2 /\ I_SCHEDULE[i,MO,s] != EMPTY /\ I_SCHEDULE[i,TH,s2] != EMPTY  /\ CLASS_DETAILS[I_SCHEDULE[i,MO,s], 4] == 1 /\ CLASS_DETAILS[I_SCHEDULE[i,TH,s2], 4] == 1)(
                   I_SCHEDULE[i,MO,s] != I_SCHEDULE[i,TH,s2])
               );
               
%LECTURES BE TAUGHT AT THE SAME TIME SLOT ON DIFFERENT DAYS (TU-FR)                  
constraint forall(i in INSTRUCTORS, s in SLOTS)(
               forall(s2 in SLOTS where s != s2 /\ I_SCHEDULE[i,TU,s] != EMPTY /\ I_SCHEDULE[i,FR,s2] != EMPTY /\ CLASS_DETAILS[I_SCHEDULE[i,TU,s], 4] == 1 /\ CLASS_DETAILS[I_SCHEDULE[i,FR,s2], 4] == 1)(
                   I_SCHEDULE[i,TU,s] != I_SCHEDULE[i,FR,s2])
               );    
                        
%2 LECTURES PER CLASS IN TOTAL               
constraint forall(c in CLASSES where c != EMPTY /\ CLASS_DETAILS[c, 4] != 3)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 2);
                                                   
%DAYS EXCEPT WEDNESDAY CANT HAVE TUTORIALS     
constraint forall(i in INSTRUCTORS, d in DAYS, s in SLOTS where d != WE)(
                (CLASS_DETAILS[I_SCHEDULE[i,d,s], 4] != 3)
                );

%EACH TUTORIAL IS ONLY TAUGHT ONLY ONCE IN THE INSTRUCTORS TIMETABLE                
constraint forall(c in CLASSES where c != EMPTY /\ CLASS_DETAILS[c, 4] == 3)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 1); 

%WEDNESDAYS ONLY HAVE LABS OR TUTORIALS (NO LECTURES)                                 
constraint forall(i in INSTRUCTORS, s in SLOTS)(
                (CLASS_DETAILS[I_SCHEDULE[i,WE,s], 4] == 3 \/ I_SCHEDULE[i,WE,s] == EMPTY)
                );
                
%EACH LECTURE CAN BE TAUGHT A MAXIMUM OF ONE TIME PER DAY                
constraint forall (day in DAYS where day != 3)(
              forall(c in CLASSES where c != EMPTY)(
                sum (i in INSTRUCTORS, s in SLOTS where i == CLASS_DETAILS[c,2])
               (if I_SCHEDULE[i,day,s] == CLASSES[c] then 1 else 0 endif) <= 1));  
               
               
%FOR ROOMS------------------------------------------------------------------------------------------------------------ 

%EACH LECTURE MUST BE TAUGHT TWICE IN ROOM SCHEDULE              
constraint forall(c in CLASSES where c != EMPTY /\ CLASS_DETAILS[c, 4] != 3)(
               sum (r in ROOMS, d in DAYS, s in SLOTS)
               (if R_SCHEDULE[r,d,s] == CLASSES[c] then 1 else 0 endif) == 2);

%EACH TUTORIAL MUST BE ONLY ONCE IN THE ROOM SCHEDULE            
constraint forall(c in CLASSES where c != EMPTY /\ CLASS_DETAILS[c, 4] == 3)(
               sum (r in ROOMS, d in DAYS, s in SLOTS)
               (if R_SCHEDULE[r,d,s] == CLASSES[c] then 1 else 0 endif) == 1);              
 
%THE TWO TIMETABLES MUST BE CONCISTENT                 
constraint forall(i in INSTRUCTORS, d in DAYS, s in SLOTS where I_SCHEDULE[i,d,s] != EMPTY)(
              sum(r in ROOMS)
                  (if R_SCHEDULE[r,d,s] == I_SCHEDULE[i,d,s] then 1 else 0 endif) == 1);                             

%CHECKS CAPACITY AND TYPE OF ROOM                                      
constraint forall(r in ROOMS, d in DAYS, s in SLOTS where R_SCHEDULE[r,d,s] != EMPTY)
                ((ROOM_DETAILS[r,3] == CLASS_DETAILS[R_SCHEDULE[r,d,s], 4] \/ CLASS_DETAILS[R_SCHEDULE[r,d,s], 4] -2 == ROOM_DETAILS[r,3]) /\ ROOM_DETAILS[r,2] >= CLASS_DETAILS[R_SCHEDULE[r,d,s], 3]);
                                                                      
solve satisfy;

output ["INSTRUCTORS:\n"];
output 
[if show_int(1,I_SCHEDULE[i,j,z]) != "1" then show_int(1,I_SCHEDULE[i,j,z] -1) else "-" endif ++ "\t" ++
if z == length(SLOTS) /\ j == length(DAYS) then "\n\n" else "" endif ++
if j == FR then "\n" endif |
i in INSTRUCTORS, z in SLOTS, j in DAYS ];

output ["\n\nROOMS:\n"];
output 
[if show_int(1,R_SCHEDULE[r,j,z]) != "1" then show_int(1,R_SCHEDULE[r,j,z] -1) else "-" endif ++ "\t" ++
if z == length(SLOTS) /\ j == length(DAYS) then "\n\n" else "" endif ++
if j == FR then "\n" endif |
r in ROOMS, z in SLOTS, j in DAYS];