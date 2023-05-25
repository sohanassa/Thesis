#!/bin/bash

printf "Reading data from Database\n"
cd tool
python3 read_input.py
printf "Done!\n"
wait

printf "Running minizinc solver (Aproximately 10 minutes)...\n"
snap run minizinc --solver chuffed data/inputData.dzn iteration5.mzn -o data/result.txt
printf "Done!\n"
wait

printf "Reading output data\n"
python3 split.py
printf "Done!\n"

printf "Creating PDF schedules\n"
python3 create_pdf_schedules.py
cd ..
printf "Done!\n"

