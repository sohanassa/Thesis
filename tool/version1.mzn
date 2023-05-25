%---------------------------------------INPUTS----------------------------------------
%INPUT FOR NUMBER OF EQUAL SLOTS PER DAY
int: numOfSlots = 8;
int: numOfWedSlots = 12;
%WILL BE CLASSES INPUT
enum CLASSES;
%WILL BE INSTRUCTORS INPUT
int: numOfInstructors;
%INPUT FOR INSTRUCTORS
enum INSTRUCTORS;
%INPUT FOR NUMBER OF CLASS CONFLICTS
int: numOfConflicts;

int: numOfParallelLectures;
%WILL BE INPUT FOR INSTRUCTORS UNAVAILABILITY 

int: num_of_lecture_rooms;
int: num_of_lab_rooms;

array [1..(numOfInstructors*5*numOfSlots)] of int:BUSY_INPUT;

array [INSTRUCTORS, SLOTS, DAYS] of int: BUSY = array3d(INSTRUCTORS, SLOTS, DAYS, BUSY_INPUT);

%WILL BE INPUT FOR INSTRUCTORS UNAVAILABILITY 
array [1..(numOfInstructors*numOfWedSlots)] of int:W_BUSY_INPUT;

array [INSTRUCTORS, W_SLOTS] of int: W_BUSY = array2d(INSTRUCTORS, W_SLOTS, W_BUSY_INPUT);

%INPUT FOR CLASS CONFLICTS
array[1..(numOfConflicts * 2)] of CLASSES: DISJUNCT_INPUT;
array[1..numOfConflicts, 1..2] of CLASSES: DISJUNCT = array2d(1..numOfConflicts, 1..2, DISJUNCT_INPUT);

%INPUT FOR PARALLEL LECTURES
array[1..(numOfParallelLectures * 2)] of CLASSES: PARALLEL_LEC_INPUT;
array[1..numOfParallelLectures, 1..2] of CLASSES: PARALLEL_LEC = array2d(1..numOfParallelLectures, 1..2, PARALLEL_LEC_INPUT);

%WILL BE INPUT FOR LINKING CLASSES TO INSTRUCTORS
%CLASS NUMBER, INSTRUCTOR NUMBER, NUMBER OF STUDENTS, TYPE: 1 FOR LECTURE, 2 FOR ONCE A WEEK LAB, 3 FOR TUTORIAL, 4 FOR TWICE A WEEK LAB, LAB NUMBER
array[1..((numOfClasses+1)*5)] of int: CLASS_DETAILS_INPUT;

array[CLASSES, 1..5] of int: CLASS_DETAILS = array2d(CLASSES, 1..5, CLASS_DETAILS_INPUT);

%---------------------------------------CONSTANTS---------------------------------------
enum DAYS = {MO, TU, WE, TH, FR};
int: numOfClasses = length(CLASSES) - 1;
set of int: SLOTS = 1..numOfSlots;
set of int: W_SLOTS = 1..numOfWedSlots;

%---------------------------------------OUTPUT------------------------------------------
%THE INSTRUCTORS SCHEDULE OUTPUT
array[INSTRUCTORS, DAYS, SLOTS] of var CLASSES: I_SCHEDULE;
array[INSTRUCTORS, W_SLOTS] of var CLASSES: I_W_SCHEDULE;

%---------------------------------------CONSTRAINTS-------------------------------------

%INSTRUCTORS CANT TEACH WHEN THEY ARE BUSY ON WEDNESDAY
constraint forall(i in INSTRUCTORS where W_BUSY[i,1] == 1 \/ W_BUSY[i,2] == 1)
                  (I_SCHEDULE[i,WE,1] == 1);                                                                                                                                                                   
 
constraint forall(i in INSTRUCTORS where W_BUSY[i,2] == 1 \/ W_BUSY[i,3] == 1)
                  (I_SCHEDULE[i,WE,2] == 1);                                                                                                                                                                   
 
constraint forall(i in INSTRUCTORS where W_BUSY[i,4] == 1 \/ W_BUSY[i,5] == 1)
                  (I_SCHEDULE[i,WE,3] == 1);                                                                                                                                                                   
 
constraint forall(i in INSTRUCTORS where W_BUSY[i,5] == 1 \/ W_BUSY[i,6] == 1)
                  (I_SCHEDULE[i,WE,4] == 1);                                                                                                                                                                   
 
constraint forall(i in INSTRUCTORS where W_BUSY[i,7] == 1 \/ W_BUSY[i,8] == 1)
                  (I_SCHEDULE[i,WE,5] == 1);                                                                                                                                                                   
 
constraint forall(i in INSTRUCTORS where W_BUSY[i,8] == 1 \/ W_BUSY[i,9] == 1)
                  (I_SCHEDULE[i,WE,6] == 1);                                                                                                                                                                   

constraint forall(i in INSTRUCTORS where W_BUSY[i,10] == 1 \/ W_BUSY[i,11] == 1)
                  (I_SCHEDULE[i,WE,7] == 1);                                                                                                                                                                   
                  
constraint forall(i in INSTRUCTORS where W_BUSY[i,11] == 1 \/ W_BUSY[i,12] == 1)
                  (I_SCHEDULE[i,WE,8] == 1);                                                                                                                                                                   
                  
%INSTRUCTOR CANT TEACH WHEN THEY ARE BUSY
constraint forall(i in INSTRUCTORS, d in DAYS, s in SLOTS where d != WE /\ BUSY[i,s,d] == 1)
                  (I_SCHEDULE[i,d,s] == 1);
                  
%INSTRUCTOR CANT TEACH WHEN THEY ARE BUSY ON WEDNESDAY
constraint forall(i in INSTRUCTORS, s in W_SLOTS where W_BUSY[i,s] == 1)
                  (I_W_SCHEDULE[i,s] == 1);

%EACH CLASS CAN ONLY BE TAUGHT BY ITS INSTRUCTOR
constraint forall(c in CLASSES where c != 1)(
              forall(i in INSTRUCTORS, d in DAYS, s in SLOTS where i != CLASS_DETAILS[c,2])
                  (I_SCHEDULE[i,d,s] != CLASSES[c]));

%LECTURES COLFLICTS  
constraint forall(f in 1..numOfConflicts)(
             forall(i in INSTRUCTORS, d in DAYS, s in SLOTS)(
               forall(i2 in INSTRUCTORS, d2 in DAYS, s2 in SLOTS where d==d2 /\ s==s2 /\ I_SCHEDULE[i,d,s] == DISJUNCT[f,1])(
                (I_SCHEDULE[i2,d2,s2] != DISJUNCT[f,2])
                )));
                
%LECTURES TAUGHT IN PARALLEL
constraint forall(f in 1..numOfParallelLectures)(
             forall(i in INSTRUCTORS, d in DAYS, s in SLOTS where d==MO \/ d==TU)(
               forall (i2 in INSTRUCTORS, d2 in DAYS, s2 in SLOTS where (d2==MO \/ d2==TU) /\ (d!=d2 \/ s!=s2) /\ (I_SCHEDULE[i,d,s] == PARALLEL_LEC[f,2]))
                (I_SCHEDULE[i2,d2,s2] != PARALLEL_LEC[f,1])
                ));
                                
%LECTURES ON MO-TH, TU-FR              
constraint forall(c in CLASSES where c != 1 /\ (CLASS_DETAILS[c,4] == 1))( %here must add labs of type 4
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS where d == MO \/ d == TH)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 2
               \/ sum (i in INSTRUCTORS, d in DAYS, s in SLOTS where d == TU \/ d == FR)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 2);
               
%LABS TYPE 4 ON MO-TH, TU-FR              
constraint forall(c in CLASSES where c != 1 /\ (CLASS_DETAILS[c,4] == 4))( 
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS where d == MO \/ d == TH)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == CLASS_DETAILS[CLASSES[c], 5] * 2
               \/ sum (i in INSTRUCTORS, d in DAYS, s in SLOTS where d == TU \/ d == FR)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == CLASS_DETAILS[CLASSES[c], 5] * 2);
               
%LECTURES BE TAUGHT AT THE SAME TIME SLOT ON DIFFERENT DAYS (MO-TH)
constraint forall(i in INSTRUCTORS, s in SLOTS)(
               forall(s2 in SLOTS where s != s2 /\ I_SCHEDULE[i,MO,s] != 1 /\ I_SCHEDULE[i,TH,s2] != 1  /\ CLASS_DETAILS[I_SCHEDULE[i,MO,s], 4] == 1 /\ CLASS_DETAILS[I_SCHEDULE[i,TH,s2], 4] == 1)(
                   I_SCHEDULE[i,MO,s] != I_SCHEDULE[i,TH,s2])
               );
               
%LECTURES BE TAUGHT AT THE SAME TIME SLOT ON DIFFERENT DAYS (TU-FR)                  
constraint forall(i in INSTRUCTORS, s in SLOTS)(
               forall(s2 in SLOTS where s != s2 /\ I_SCHEDULE[i,TU,s] != 1 /\ I_SCHEDULE[i,FR,s2] != 1 /\ CLASS_DETAILS[I_SCHEDULE[i,TU,s], 4] == 1 /\ CLASS_DETAILS[I_SCHEDULE[i,FR,s2], 4] == 1)(
                   I_SCHEDULE[i,TU,s] != I_SCHEDULE[i,FR,s2])
               );
               
%LABS OF TYPE 4 BE TAUGHT AT THE SAME TIME SLOT ON DIFFERENT DAYS (MO-TH) OR (TU-FR)              
constraint forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 4)(
               sum (s in SLOTS)
               (if I_SCHEDULE[INSTRUCTORS[CLASS_DETAILS[c, 2]],TU,s] == I_SCHEDULE[INSTRUCTORS[CLASS_DETAILS[c, 2]],FR,s] /\ I_SCHEDULE[INSTRUCTORS[CLASS_DETAILS[c, 2]],TU,s] == c then 1 else 0 endif) == CLASS_DETAILS[c, 5]
               \/
               sum (s in SLOTS)
               (if I_SCHEDULE[INSTRUCTORS[CLASS_DETAILS[c, 2]],MO,s] == I_SCHEDULE[INSTRUCTORS[CLASS_DETAILS[c, 2]],TH,s] /\ I_SCHEDULE[INSTRUCTORS[CLASS_DETAILS[c, 2]],MO,s] == c then 1 else 0 endif) == CLASS_DETAILS[c, 5]);
             

%2 LECTURES PER CLASS IN TOTAL               
constraint forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 1)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 2);

%1 LECTURES PER CLASS OF TYPE 5 IN TOTAL               
constraint forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 5)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == 1);
                                                                  
%WEDNESDAYS IN I_SCHEDULE ONLY HAVE LABS (NO LECTURES)                                 
constraint forall(i in INSTRUCTORS, s in SLOTS)(
                (CLASS_DETAILS[I_SCHEDULE[i,WE,s], 4] == 2 \/ I_SCHEDULE[i,WE,s] == 1)
                );
                
%EACH LECTURE CAN BE TAUGHT A MAXIMUM OF ONE TIME PER DAY                
constraint forall (day in DAYS where day != 3)(
              forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 1)(
                sum (i in INSTRUCTORS, s in SLOTS where i == CLASS_DETAILS[c,2])
               (if I_SCHEDULE[i,day,s] == CLASSES[c] then 1 else 0 endif) <= 1)); 

%MAXIMUM CAPACITY FOR HOW MANY LECTURE CAN BE TAUGHT AT THE SAME TIME    
constraint forall(d in DAYS, s in SLOTS)(
              sum(i in INSTRUCTORS where CLASS_DETAILS[I_SCHEDULE[i,d,s],4] == 1)
                (if I_SCHEDULE[i,d,s] != 1 then 1 else 0 endif) <= num_of_lecture_rooms %CAPACITY FOR LECTURE ROOMS
           );
%MAXIMUM CAPACITY FOR HOW MANY LABS CAN BE TAUGHT AT THE SAME TIME             
constraint forall(d in DAYS, s in SLOTS)(
              sum(i in INSTRUCTORS where CLASS_DETAILS[I_SCHEDULE[i,d,s],4] == 2 \/ CLASS_DETAILS[I_SCHEDULE[i,d,s],4] == 4)
                (if I_SCHEDULE[i,d,s] != 1 then 1 else 0 endif) <= num_of_lab_rooms %CAPACITY FOR LAB ROOMS
           );
%MAXIMUM CAPACITY FOR HOW MANY TUTORIALS CAN BE TAUGHT AT THE SAME TIME  
constraint forall(s in W_SLOTS)(
              sum(i in INSTRUCTORS where CLASS_DETAILS[I_W_SCHEDULE[i,s],4] == 3)
                (if I_W_SCHEDULE[i,s] != 1 then 1 else 0 endif) <= num_of_lecture_rooms %CAPACITY FOR LECTURE ROOMS
           );
           
%LABS OF TYPE 2 ARE TAUGHT IN WEEK AS MANY TIMES AS CLASS DETAILS SAYS      
constraint forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 2)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == CLASS_DETAILS[CLASSES[c], 5]);
               
%LABS OF TYPE 4 ARE TAUGHT IN WEEK AS MANY TIMES AS CLASS DETAILS SAYS    
constraint forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 4)(
               sum (i in INSTRUCTORS, d in DAYS, s in SLOTS)
               (if I_SCHEDULE[i,d,s] == CLASSES[c] then 1 else 0 endif) == (CLASS_DETAILS[CLASSES[c], 5] * 2));
               
%---------------------------------------WEDNESDAY-SCHEDULE----------------------------------        

%TUTORIALS COLFLICTS  
constraint forall(f in 1..numOfConflicts)(
             forall(i in INSTRUCTORS, s in W_SLOTS)(
               forall(i2 in INSTRUCTORS, s2 in W_SLOTS where s==s2 /\ I_W_SCHEDULE[i,s] == DISJUNCT[f,1])(
                (I_W_SCHEDULE[i2,s2] != DISJUNCT[f,2])
                )));
                
%TUTORIALS TAUGHT IN PARALLEL
constraint forall(f in 1..numOfParallelLectures)(
             forall(i in INSTRUCTORS, s in W_SLOTS)(
               forall (i2 in INSTRUCTORS, s2 in W_SLOTS where s!=s2 /\ (I_W_SCHEDULE[i,s] == PARALLEL_LEC[f,2]))
                (I_W_SCHEDULE[i2,s2] != PARALLEL_LEC[f,1])
                ));
                
%I_SCHEDULE DOES NOT HAVE TUTORIALS    
constraint forall(i in INSTRUCTORS, d in DAYS, s in SLOTS)(
                (CLASS_DETAILS[I_SCHEDULE[i,d,s], 4] != 3)
                );                  

%WEDNESDAY SCHEDULE CAN ONLY HAVE TUTORIALS
constraint forall(i in INSTRUCTORS, s in W_SLOTS)(
                  (CLASS_DETAILS[I_W_SCHEDULE[i,s], 4] == 3 \/ I_W_SCHEDULE[i,s] == 1)
            );
            
%EACH TUTORIAL IS ONLY TAUGHT ONCE IN THE I_W_TIMETABLE             
constraint forall (c in CLASSES where c != 1 /\ CLASS_DETAILS[c, 4] == 3)(
                  sum(i in INSTRUCTORS, s in W_SLOTS)
                  (if I_W_SCHEDULE[i,s] == c then 1 else 0 endif) == 1
            );
            
%EACH TUTORIAL CAN ONLY BE TAOUGHT BY ITS INSTRUCTOR
constraint forall(c in CLASSES where c != 1 /\ CLASS_DETAILS[c,4] == 3)(
              forall(i in INSTRUCTORS, s in W_SLOTS where i != CLASS_DETAILS[c,2])
                  (I_W_SCHEDULE[i,s] != CLASSES[c]));
                  
%MAKING SURE INSTRUCTORS DONT HAVE LABS AND TUTORIAL AT THE SAME TIME IN THE 2 DIFFERENT SHCEDULES
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,1] == 1 \/ (I_W_SCHEDULE[i,1] == 1 /\ I_W_SCHEDULE[i,2] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,2] == 1 \/ (I_W_SCHEDULE[i,2] == 1 /\ I_W_SCHEDULE[i,3] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,3] == 1 \/ (I_W_SCHEDULE[i,4] == 1 /\ I_W_SCHEDULE[i,5] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,4] == 1 \/ (I_W_SCHEDULE[i,5] == 1 /\ I_W_SCHEDULE[i,6] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,5] == 1 \/ (I_W_SCHEDULE[i,7] == 1 /\ I_W_SCHEDULE[i,8] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,6] == 1 \/ (I_W_SCHEDULE[i,8] == 1 /\ I_W_SCHEDULE[i,9] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,7] == 1 \/ (I_W_SCHEDULE[i,10] == 1 /\ I_W_SCHEDULE[i,11] == 1))
            );
constraint forall(i in INSTRUCTORS)(
                  (I_SCHEDULE[i,WE,8] == 1 \/ (I_W_SCHEDULE[i,11] == 1 /\ I_W_SCHEDULE[i,12] == 1))
            );
              
%--------------------------------------------SOLVING----------------------------------------
                            
solve satisfy;

%---------------------------------------PRINTING-OUTPUT-------------------------------------
output ["INSTRUCTORS SCHEDULE:\n"];
output 
[if z==1 /\ j==MO then "Instructor: " ++ show_int(1,i) ++ "\n" else "" endif ++ if show_int(1,I_SCHEDULE[i,j,z]) != "1" then show_int(1,I_SCHEDULE[i,j,z] -1) else "-" endif ++ "," ++
if z == length(SLOTS) /\ j == length(DAYS) then "\n" else "" endif ++
if j == FR then "\n" endif |
i in INSTRUCTORS, z in SLOTS, j in DAYS ];

output ["WEDNESDAY SCHEDULE:\n"];
output 
[if s==1 then "Instructor: " ++ show_int(1,i) ++ "\n" else "" endif ++ if show_int(1,I_W_SCHEDULE[i,s]) != "1" then show_int(1,I_W_SCHEDULE[i,s] -1) else "-" endif ++ "\n" ++
if s == length(W_SLOTS) then "\n" else "" endif|
i in INSTRUCTORS, s in W_SLOTS];