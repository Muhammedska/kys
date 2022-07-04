import os

from PyQt5 import QtWidgets, uic
from PyQt5.QtWidgets import *
from PyQt5 import QtCore,QtGui
from PyQt5.QtCore import QTimer,QUrl
from PyQt5.QtGui import QPixmap,QIcon

import sqlite3
import requests
import sys
from bs4 import BeautifulSoup
import csv
import urllib3

urllib3.disable_warnings()
global tokenx,webad,con
db_path = './app.db'
con = sqlite3.connect(db_path)
tokenx = con.execute("""SELECT * FROM app WHERE var='token'""").fetchall()[0][1]
webad = con.execute("""SELECT * FROM app WHERE var='web'""").fetchall()[0][1]

class Ui(QtWidgets.QMainWindow):
    def __init__(self):
        super(Ui, self).__init__()
        uic.loadUi('./app.ui', self)
        flags = QtCore.Qt.WindowFlags(QtCore.Qt.FramelessWindowHint | QtCore.Qt.WindowStaysOnTopHint)
        self.setWindowFlags(flags)

        self.closex.clicked.connect(lambda :app.exit())
        self.save.clicked.connect(self.saveConf)
        self.uploadst.clicked.connect(self.upload)
        self.logreq.clicked.connect(self.takelogic)
        self.notifysend.clicked.connect(self.send_notify)
        self.syprexrunner.clicked.connect(self.syprexcomrunner)
        self.token.setText(tokenx)
        self.alanad.setText(webad)
    def saveConf(self):
        global tokenx,webad,con
        Token = self.token.text()
        webserver = self.alanad.text()
        con.execute("UPDATE `app` SET `val`='"+Token+"' WHERE var = 'token' ;")
        con.execute("UPDATE `app` SET `val`='" + webserver + "' WHERE var = 'web' ;")
        con.commit()
        con.close()
        con = sqlite3.connect(db_path)
        tokenx = con.execute("""SELECT * FROM app WHERE var='token'""").fetchall()[0][1]
        webad = con.execute("""SELECT * FROM app WHERE var='web'""").fetchall()[0][1]

        self.token.setText(tokenx)
        self.alanad.setText(webad)
    def upload(self):
        options = QFileDialog.Options()
        options |= QFileDialog.DontUseNativeDialog
        fileName, _ = QFileDialog.getOpenFileName(self, "QFileDialog.getOpenFileName()", "","Excel Files (*.csv)", options=options)
        file = open(fileName,'r',encoding='UTF-8').read().replace('\ufeff','').split('\n')
        lens = len(file)
        q = 0
        index = 0
        f = 0
        indi =[]
        for i in file:
            if len(i) == 0:
                break
            self.setWindowTitle(str(q)+' Kişi yüklendi | '+str(f)+' Kişi Yüklenemedi ')
            m = i.split(';')

            if len(m) == 0:

                break
            r = requests.get(webad+'rgx/core.php',params={"token":tokenx,"type":"add","t":"student","id":m[0],"name":m[1],"grade":m[2]})
            if str(r.content.decode("utf-8")) == "success":
                q+=1
            else:
                f+=1
                indi.append(index)
            index+=1
        log = open('logoutupload.txt','w',encoding='UTF-8')
        log.write(str(q)+' Kişi yüklendi | '+str(f)+' Kişi Yüklenemedi \n')
        for i in indi:
            log.write(file[i]+'\n')
        log.close()
        if len(indi) == 0:
            QMessageBox.information(self,'WALLE',str(q)+' Kişi yüklendi | '+str(f)+' Kişi Yüklenemedi ')
        else:
            QMessageBox.warning(self,'WALLE',str(q)+' Kişi yüklendi | '+str(f)+' Kişi Yüklenemedi \n Bazı kişiler aktarılırken sorun ile karşılaşıldı.\n karşıya yükleme kayıt günlüğü açılacaktır.')
        self.showMinimized()
        os.system('notepad ./logoutupload.txt')
    def takelogic(self):
        r = requests.get(webad + 'rgx/core.php',params={"token": tokenx, "type": "getlog"})
        m = r.content.decode('UTF-8')
        log = open('weblog.txt', 'w', encoding='UTF-8')
        log.write(m)
        log.close()
        QMessageBox.information(self, 'WALLE', 'Log kayıtları içeri aktarıldı dosya açılıyor.')
        self.showMinimized()
        os.system('notepad ./weblog.txt')
    def send_notify(self):
        mas = self.mass.currentText()
        if mas == 'Genel':
            mas = 'all'
        elif mas == 'Öğrenci':
            mas = 'student'
        elif mas == 'Öğretmen':
            mas = 'teacher'
        text = self.nottext.document().toPlainText()
        if len(text) == 0 :
            QMessageBox.warning(self, 'WALLE', 'Bildirim metni Girilmemiş')
        else:
            r = requests.get(webad + 'rgx/core.php', params={"token": tokenx, "type": "sendnotify", "mass":mas,"notifytext":text})
            self.nottext.setPlainText("")
            QMessageBox.information(self,'WALLE','Bildirim Yollandı')
    def syprexcomrunner(self):
        syprex_command = 'MuhammedEfendimizÇokYaşa'
        syprex_text = self.syprex.text()
        if  syprex_text == syprex_command:
            self.stu = student_login()
            self.stu.show()
            self.close()



class student_login(QtWidgets.QMainWindow):
    def __init__(self):
        super(student_login, self).__init__()
        uic.loadUi('./stu.ui', self)
        h = QtWidgets.QDesktopWidget().screenGeometry().height()
        w = QtWidgets.QDesktopWidget().screenGeometry().width()

        self.base.move(int((w-400)/2),int((h-540)/2))
        self.syprexrunner.clicked.connect(self.syprexcomrunner)
        self.login.clicked.connect(self.login_)

    def login_(self):
        r = requests.get(webad+'/rgx/core.php',{'token':tokenx,'type':'istudent','id':self.uid.text()})
        print(r.content.decode('utf-8'))
    def syprexcomrunner(self):
        syprex_command = 'BengiSuARTZ'
        syprex_text = self.syprex.text()
        if  syprex_text == syprex_command:
            self.close()


class student(QtWidgets.QMainWindow):
    def __init__(self):
        super(student, self).__init__()
        uic.loadUi('./student.ui', self)

if __name__ == "__main__":

    app = QtWidgets.QApplication(sys.argv)
    window = student_login()
    window.showFullScreen()
    app.exec_()