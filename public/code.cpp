#include<iostream>
#include<unistd.h>
#include<sys/wait.h>

#include<signal.h>
using namespace std;
void zhand(int signum){
    wait(NULL);
    cout<<"zombie signal handleld"<<endl;

}

int main(){
    int fid = fork();
    if (fid == 0)
    {
        cout<<"thanks to sigchild handle i wont be a zombie"<<endl;

        /* code */
    }
    else
    {
        signal(SIGCHLD , zhand);


        sleep(10);
        /* code */
    }
    


}