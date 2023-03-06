
#pip install SpeechRecognition pyaudio
#install library first
import speech_recognition as sr

r = sr.Recognizer()

with sr.Microphone() as source:
    r.adjust_for_ambient_noise(source)
    print("Say something!")
#انا شايف 3 ثواني كفاية
    audio = r.listen(source, phrase_time_limit=3)
    
try:
    text = r.recognize_google(audio)
    print("You said: " + text)
except sr.UnknownValueError:
    print("Sorry, I could not understand what you said.")
except sr.RequestError:
    print("Sorry, my speech service is currently down.")
