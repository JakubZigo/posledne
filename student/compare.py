import sys
from sympy import *
from sympy import simplify
from sympy.parsing.latex import parse_latex

def round_expr(expr, num_digits):
    return expr.xreplace({n : round(n, num_digits) for n in expr.atoms(Number)})

def compare(expr1, expr2):
    try:
        expr1 = parse_latex(expr1).evalf()
        expr2 = parse_latex(expr2).evalf()
        expr1_rounded = round_expr(expr1, 4)
        expr2_rounded = round_expr(expr2, 4)
    except Exception as e:
        print(f"An error occurred while parsing the latex: {str(e)}")
        return 0
    if (expr1_rounded.equals(expr2_rounded)):
        return 1
    else:
        return 0

expr1 = sys.stdin.readline().strip()
expr2 = sys.stdin.readline().strip()
print(compare(expr1, expr2))