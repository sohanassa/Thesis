#!/bin/bash

printf "Reading data from Database\n"
cd tool
python3 read_input.py
printf "Done!\n"
wait

printf "Running minizinc solver\n"
snap run minizinc --solver chuffed data/inputData.dzn iteration3.mzn -o data/result.txt
printf "Done!\n"
wait

printf "Reading output data\n"
python3 read_output.py
cd ..
printf "Done!\n"

