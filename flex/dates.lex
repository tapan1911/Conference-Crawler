%{
#include <stdio.h>
#include<string.h>
int deadlines=0;
int paper_deadlines=0,lines=0,submissions=0;
int flag1=0,flag2=0,flag3=0,flag4=0,flag5=0,flag6=0,flag7=0,flag8=0;
int i=0,j;
char str[500]="";
%}

%%
. { 
	if(flag3==lines)
	{
		if(flag5==lines)
		{
			str[i]=yytext[0];
			printf("%c",str[i]);
			i++;
			if(i>498)
			{
				strcpy(str,"");
				i=0;
				flag3=0;
			}
		}
	}
	if(flag7==lines)
	{
		str[i]=yytext[0];
		i++;
		if(i>498)
                {
                        strcpy(str,"");
                        i=0;
                }
	}
}
"Paper" {
	paper_deadlines++;
	flag1=lines;
	//printf("%d\n",lines);
}
"Deadline"	{
	if(flag1==lines)
	{
		flag1=0;	
		flag2=lines;
		deadlines++;
	//	printf("    %d\n",lines);	
	}
}
"submission"	{
	submissions++;
	flag6=lines;
}
[0-9][0-9]" "[A-Z][a-z][a-z]" "[0-9][0-9][0-9][0-9] {
	if(flag2==lines)
	{
		flag2=0;
		printf("\n%s",yytext);
		flag4=1;
	}
	if(flag6==lines)
	{
		flag6=0;
		printf("\n%s",yytext);
		flag8=1;
	}
}
[0-9][0-9]" "[A-Z][a-z][a-z]" "[0-9][0-9]  {
	if(flag2==lines)
        {
                flag2=0;
                printf("\n%s",yytext);
                flag4=1;
        }
        if(flag6==lines)
        {
                flag6=0;
                printf("\n%s",yytext);
		flag8=1;
		/*str[i]='\0';
                printf("\n%s\n",str);
        	strcpy(str,"");
        	i=0;
		flag7=0;*/
	}	
}
"tooltip.show('" {
	flag7=lines;
}
"<td valign=" {
	flag3=lines;
}
"</td>" {
	flag3=0;
	if(flag4==1)
	{
		flag4=0;
		str[i]='\0';
		printf("\n%s\n",str);
	}
	strcpy(str,"");
	i=0;
}
"<br"	{
	flag7=0;
	if(flag8==1)
        {
                flag8=0;
                str[i]='\0';
                printf("\n%s\n",str);
        }
        strcpy(str,"");
        i=0;
}
"<b>" {
	flag5=lines;
}
"</b>" {
	flag5=0;
}
\n { 
	lines++;	
}

%%

int yywrap()
{
return;
}
int main()
{
        yylex();
	printf("\n-D-%d -PD-%d -S-%d\n",deadlines,paper_deadlines,submissions);
	return 0;
}
