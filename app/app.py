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
import pandas
import urllib3

urllib3.disable_warnings()
global tokenx,webad,con,reqte,stuidd
db_path = './app.db'
con = sqlite3.connect(db_path)
tokenx = con.execute("""SELECT * FROM app WHERE var='token'""").fetchall()[0][1]
webad = con.execute("""SELECT * FROM app WHERE var='web'""").fetchall()[0][1]
reqte = []
stuidd = ''
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
        fileName, _ = QFileDialog.getOpenFileName(self, "QFileDialog.getOpenFileName()", "","Excel Files (*.xlsx);; Excel Files (*.csv)", options=options)
        if fileName.endswith('.csv'):
            file = open(fileName, 'r', encoding='UTF-8').read().replace('\ufeff', '').split('\n')
            lens = len(file)
            q = 0
            index = 0
            f = 0
            indi = []
            for i in file:
                if len(i) == 0:
                    break
                self.setWindowTitle(str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi ')
                m = i.split(';')

                if len(m) == 0:
                    break
                r = requests.get(webad + '/rgx/core.php',params={"token": tokenx, "type": "add", "t": "student", "id": m[0], "name": m[1],"grade": m[2]}, timeout=1)
                self.progressBar.setValue(int(q / lens * 100))
                self.sysoutput.appendPlainText(i + '  ' + str(r.content.decode("utf-8")) + '\n')
                if str(r.content.decode("utf-8")) == "success":
                    q += 1
                else:
                    f += 1
                    indi.append(index)
                index += 1
            log = open('logoutupload.txt', 'w', encoding='UTF-8')
            log.write(str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi \n')
            for i in indi:
                log.write(file[i] + '\n')
            log.close()
            if len(indi) == 0:
                QMessageBox.information(self, 'WALLE', str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi ')
            else:
                QMessageBox.warning(self, 'WALLE', str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi \n Baz?? ki??iler aktar??l??rken sorun ile kar????la????ld??.\n kar????ya y??kleme kay??t g??nl?????? a????lacakt??r.')
        elif fileName.endswith('.xlsx'):
            file = pandas.read_excel(open(fileName,'rb'),header=None)
            dataList = file.values.tolist()
            lens = len(dataList)
            q = 0
            index = 0
            f = 0
            indi = []
            for i in dataList:
                if len(i) == 0:
                    break
                self.setWindowTitle(str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi ')

                r = requests.get(webad + '/rgx/core.php',params={"token": tokenx, "type": "add", "t": "student", "id": i[0], "name": i[1],"grade": i[2]}, timeout=1)
                self.progressBar.setValue(int(q / lens * 100))
                self.sysoutput.appendPlainText(str(i[0] )+ '  ' +i[1] + '  ' +str(i[2]) + '  ' + str(r.content.decode("utf-8")) + '\n')
                if str(r.content.decode("utf-8")) == "success":
                    q += 1
                else:
                    f += 1
                    indi.append(index)
                index += 1
            log = open('logoutupload.txt', 'w', encoding='UTF-8')
            log.write(str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi \n')
            for i in indi:
                log.write(str(dataList[i]) + '\n')
            log.close()
            if len(indi) == 0:
                QMessageBox.information(self, 'WALLE', str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi ')
                sspdx = True
            else:
                QMessageBox.warning(self, 'WALLE', str(q) + ' Ki??i y??klendi | ' + str(f) + ' Ki??i Y??klenemedi \n Baz?? ki??iler aktar??l??rken sorun ile kar????la????ld??.\n kar????ya y??kleme kay??t g??nl?????? a????lacakt??r.')

        if fileName != '' and sspdx == False:
            self.showMinimized()
            os.system('notepad ./logoutupload.txt')
    def takelogic(self):
        r = requests.get(webad + '/rgx/core.php',params={"token": tokenx, "type": "getlog"},timeout=1)
        m = r.content.decode('UTF-8')
        self.sysoutput.appendPlainText('Log Kay??tlar?? al??nd??'+ '\n')
        log = open('weblog.txt', 'w', encoding='UTF-8')
        log.write(m)
        log.close()
        QMessageBox.information(self, 'WALLE', 'Log kay??tlar?? i??eri aktar??ld?? dosya a????l??yor.')
        self.showMinimized()
        os.system('notepad ./weblog.txt')
    def send_notify(self):
        mas = self.mass.currentText()
        if mas == 'Genel':
            mas = 'all'
        elif mas == '????renci':
            mas = 'student'
        elif mas == '????retmen':
            mas = 'teacher'
        text = self.nottext.document().toPlainText()
        if len(text) == 0 :
            QMessageBox.warning(self, 'WALLE', 'Bildirim metni Girilmemi??')
        else:
            r = requests.get(webad + '/rgx/core.php', params={"token": tokenx, "type": "sendnotify", "mass":mas,"notifytext":text},timeout=1)
            self.nottext.setPlainText("")
            QMessageBox.information(self,'WALLE','Bildirim Yolland??')
    def syprexcomrunner(self):
        syprex_command = 'MuhammedEfendimiz??okYa??a'
        syprex_text = self.syprex.text()
        if  syprex_text == syprex_command:
            self.stu = student_login()
            self.stu.showFullScreen()
            self.close()
        elif syprex_text == 'cleanstudent':
            r = requests.get(webad + '/rgx/core.php', params={"token": tokenx, "type": "clearstudent"},timeout=1)
            m = r.content.decode('UTF-8')
            if m == 'success':
                self.sysoutput.appendPlainText('????renci Kay??tlat?? temizlendi\n')
            else:
                self.sysoutput.appendPlainText(m+'\n')

class student_login(QtWidgets.QMainWindow):
    def __init__(self):
        super(student_login, self).__init__()
        uic.loadUi('./stu.ui', self)
        h = QtWidgets.QDesktopWidget().screenGeometry().height()
        w = QtWidgets.QDesktopWidget().screenGeometry().width()

        flags = QtCore.Qt.WindowFlags(QtCore.Qt.FramelessWindowHint | QtCore.Qt.WindowStaysOnTopHint)
        self.setWindowFlags(flags)

        self.base.move(int((w-400)/2),int((h-540)/2))
        self.syprexrunner.clicked.connect(self.syprexcomrunner)
        self.login.clicked.connect(self.login_)

    def login_(self):
        global reqte,stuidd
        r = requests.get(webad+'/rgx/core.php',{'token':tokenx,'type':'istudent','id':self.uid.text()},timeout=1)
        rqm = r.content.decode('utf-8')

        if rqm != 'error':
            stuidd = self.uid.text()
            rqq = r.content.decode('utf-8').split(',')
            reqte = rqq
            self.reqcretor = student()
            self.reqcretor.showFullScreen()
            self.close()
        else:
            QMessageBox.warning(self,'WALLE','Bu '+self.uid.text()+' ????renci Kay??tl?? de??il')
            self.uid.setText('')
        print()
    def syprexcomrunner(self):
        syprex_command = 'BengiSuARTZ'
        syprex_text = self.syprex.text()
        if  syprex_text == syprex_command:
            self.close()


class student(QtWidgets.QMainWindow):
    def __init__(self):
        super(student, self).__init__()
        uic.loadUi('./stuf.ui', self)

        flags = QtCore.Qt.WindowFlags(QtCore.Qt.FramelessWindowHint | QtCore.Qt.WindowStaysOnTopHint)
        self.setWindowFlags(flags)

        h = QtWidgets.QDesktopWidget().screenGeometry().height()
        w = QtWidgets.QDesktopWidget().screenGeometry().width()
        self.base.move(int((w-400)/2),int((h-540)/2))
        self.syprexrunner.clicked.connect(self.syprexcomrunner)
        self.send.clicked.connect(self.sendreq)
        self.qses.clicked.connect(self.sesclose)

        les = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"]
        nidix = 0
        for i in les:
            if i not in reqte:
                self.reqlist.addItem(i.upper())
                nidix +=  1
        if nidix == 0:
            QMessageBox.information(self,'WALLE','T??m derslerden ??nceden randevu al??nm????t??r.')
    def sendreq(self):
        rm = self.reqlist.currentText().lower()
        r = requests.get(webad+'/rgx/core.php',{'token':tokenx,'type':'reqadd','id':stuidd,'lesson':rm},timeout=1)
        if r.content.decode('utf-8') == 'success':
            QMessageBox.information(self,'WALLE',self.reqlist.currentText()+' Talebiniz al??nm????t??r.\n??Y?? G??NLER D??LER??Z')
            self.reqlist.clear()
            reqte.append(rm)
            les = ["matematik", "turkce", "geometri", "kimya", "fizik", "biyoloji", "tarih", "cografya"]
            nidix = 0
            for i in les:
                if i not in reqte:
                    self.reqlist.addItem(i.upper())
                    nidix += 1
            if nidix == 0:
                QMessageBox.information(self, 'WALLE', 'T??m derslerden randevu al??nm????t??r.')
    def sesclose(self):
        global stuidd
        stuidd = ''
        self.stu = student_login()
        self.stu.showFullScreen()
        self.close()
    def syprexcomrunner(self):
        syprex_command = '25032022'
        syprex_text = self.syprex.text()
        if syprex_text == syprex_command:
            self.close()

if __name__ == "__main__":

    app = QtWidgets.QApplication(sys.argv)
    window = Ui()
    window.show()
    app.exec_()