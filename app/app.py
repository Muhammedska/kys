from PyQt5 import QtWidgets, uic
from PyQt5.QtWidgets import *
from PyQt5 import QtCore
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
print(tokenx)
class Ui(QtWidgets.QMainWindow):
    def __init__(self):
        super(Ui, self).__init__()
        uic.loadUi('./app.ui', self)
        flags = QtCore.Qt.WindowFlags(QtCore.Qt.FramelessWindowHint | QtCore.Qt.WindowStaysOnTopHint)
        self.setWindowFlags(flags)

        self.closex.clicked.connect(lambda :app.exit())
        self.save.clicked.connect(self.saveConf)
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

class student_login(QtWidgets.QMainWindow):
    def __init__(self):
        super(student_login, self).__init__()
        uic.loadUi('./student_login.ui', self)

class student(QtWidgets.QMainWindow):
    def __init__(self):
        super(student, self).__init__()
        uic.loadUi('./student.ui', self)

if __name__ == "__main__":

    app = QtWidgets.QApplication(sys.argv)
    window = Ui()
    window.show()
    app.exec_()