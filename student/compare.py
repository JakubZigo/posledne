import sys
from sympy import simplify
from sympy.parsing.latex import parse_latex

def compare(expr1, expr2):
    try:
        expr1 = parse_latex(expr1)
        expr2 = parse_latex(expr2)
    except Exception as e:
        print(f"An error occurred while parsing the latex: {str(e)}")
        return 0
    if (simplify(expr1 - expr2) == 0):
        return 1
    else:
        return 0

expr1 = sys.stdin.readline().strip()
expr2 = sys.stdin.readline().strip()
print(compare(expr1, expr2))