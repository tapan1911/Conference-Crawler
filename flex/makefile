all : dates

dates : lex.yy.c
	gcc lex.yy.c -o dates

lex.yy.c : dates.lex
	flex dates.lex


