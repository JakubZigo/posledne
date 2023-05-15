import sys
from sympy import simplify
from sympy.parsing.latex import parse_latex

def compare(expr1, expr2):
    expr1 = parse_latex(expr1)
    expr2 = parse_latex(expr2)
    return simplify(expr1 - expr2) == 0

expr1 = sys.argv[1]
expr2 = sys.argv[2]
print(compare(expr1, expr2))

# import sys
# from sympy import simplify
# from sympy.parsing.latex import parse_latex
#
# def compare(expr1, expr2):
#     expr1 = parse_latex(expr1)
#     expr2 = parse_latex(expr2)
#     return simplify(expr1 - expr2) == 0
#
# expr1 = sys.stdin.readline().strip()
# expr2 = sys.stdin.readline().strip()
# print(compare(expr1, expr2))