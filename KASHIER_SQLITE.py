import tkinter as tk
from tkinter import messagebox
import subprocess
import time
import os
import sys

class KASHIERControlApp:
    def __init__(self, root):
        self.root = root
        self.root.title("برنامج كاشير")
        self.root.configure(bg="#1b2624")
        self.root.overrideredirect(1)  # Remove window decorations (header)

        # Create the main widgets
        self.create_widgets()
        self.is_running = False
        self.root.protocol("WM_DELETE_WINDOW", self.on_closing)

        # Determine the directory of the current script
        self.script_dir = os.path.dirname(os.path.abspath(__file__))

        # Center the window on the screen
        self.center_window()


    def create_widgets(self):
        # Create a frame with a white border
        self.main_frame = tk.Frame(self.root, bg="#1b2624", borderwidth=5, relief="solid")
        self.main_frame.pack(padx=10, pady=10, fill=tk.BOTH, expand=True)

        self.welcome_label = tk.Label(self.main_frame, text="مرحبا بك في برنامج الكاشير", bg="#1b2624", fg="white", font=("Arial", 16))
        self.welcome_label.pack(pady=10)

        self.start_button = tk.Button(self.main_frame, text="بدء التشغيل", bg="green", fg="white", command=self.start_servers, font=("Arial", 14), width=15, height=2)
        self.start_button.pack(pady=10)
        
        self.stop_button = tk.Button(self.main_frame, text="إيقاف التشغيل", bg="red", fg="white", command=self.stop_servers, font=("Arial", 14), width=15, height=2)
        self.stop_button.pack(pady=10)
        self.stop_button.pack_forget()  # Hide the stop button initially
        
        self.status_label = tk.Label(self.main_frame, text="الحالة: التطبيق متوقف", bg="#1b2624", fg="white", font=("Arial", 12))
        self.status_label.pack(pady=10)

    def center_window(self):
        window_width = 300
        window_height = 200

        # Get the screen width and height
        screen_width = self.root.winfo_screenwidth()
        screen_height = self.root.winfo_screenheight()

        # Calculate the position of the window
        x = (screen_width // 2) - (window_width // 2)
        y = (screen_height // 2) - (window_height // 2)

        # Set the window size and position
        self.root.geometry(f"{window_width}x{window_height}+{x}+{y}")

    def start_servers(self):
        if not self.is_running:
            self.status_label.config(text="جاري التشغيل...")
            self.root.update()
            try:
                os.chdir(self.script_dir)  # Change to the script directory
                self.laravel_process = subprocess.Popen(["php", "artisan", "serve"])

                time.sleep(5)  # Wait for Laravel to start

                subprocess.Popen(["start", "", "http://localhost:8000"], shell=True)

                self.status_label.config(text="الحالة: التطبيق يعمل")
                self.start_button.pack_forget()  # Hide the start button
                self.stop_button.pack(pady=10)  # Show the stop button
                self.is_running = True
            except Exception as e:
                messagebox.showerror("خطأ", str(e))
        else:
            messagebox.showinfo("معلومات", "الخوادم تعمل بالفعل.")

    def stop_servers(self):
        if self.is_running:
            self.status_label.config(text="جاري الإيقاف...")
            self.root.update()
            try:
                # Terminate Laravel process
                self.laravel_process.terminate()
                self.laravel_process.wait(timeout=10)
                self.laravel_process.kill()

                self.status_label.config(text="الحالة: متوقف")
                self.start_button.pack(pady=10)  # Show the start button
                self.stop_button.pack_forget()  # Hide the stop button
                self.is_running = False
                
                # Close the Tkinter window and terminate the script
                self.root.destroy()
                sys.exit()
            except Exception as e:
                messagebox.showerror("خطأ", str(e))

    def on_closing(self):
        self.stop_servers()

if __name__ == "__main__":
    root = tk.Tk()
    app = KASHIERControlApp(root)
    root.mainloop()
