#include <iostream>
#include <vector>

using namespace std;

// Ukuran dimensi labirin N x N
int N;

// Fungsi untuk mencetak matriks solusi ke layar
void cetakSolusi(const vector<vector<int>>& sol) {
    cout << "\n=========================================\n";
    cout << "  JALUR SOLUSI LABIRIN YANG DITEMUKAN\n";
    cout << "=========================================\n";
    for (int i = 0; i < N; i++) {
        cout << "      ";
        for (int j = 0; j < N; j++) {
            if (sol[i][j] == 1) {
                cout << "[*] "; // Bagian dari jalur sukses
            } else {
                cout << " .  "; // Sel yang tidak dilewati
            }
        }
        cout << "\n";
    }
    cout << "=========================================\n";
}

// Fungsi kelayakan untuk memeriksa apakah koordinat (x, y) layak dikunjungi
bool apakahLayak(const vector<vector<int>>& maze, int x, int y, const vector<vector<int>>& sol) {
    return (x >= 0 && x < N && y >= 0 && y < N && maze[x][y] == 1 && sol[x][y] == 0);
}

// Fungsi rekursif Backtracking
bool selesaikanMazeRekursif(const vector<vector<int>>& maze, int x, int y, vector<vector<int>>& sol) {
    // BASIS: Jika sudah mencapai tujuan di kanan bawah (N-1, N-1)
    if (x == N - 1 && y == N - 1) {
        sol[x][y] = 1;
        return true;
    }

    if (apakahLayak(maze, x, y, sol)) {
        sol[x][y] = 1;

        if (selesaikanMazeRekursif(maze, x + 1, y, sol)) return true; // BAWAH
        if (selesaikanMazeRekursif(maze, x, y + 1, sol)) return true; // KANAN
        if (selesaikanMazeRekursif(maze, x - 1, y, sol)) return true; // ATAS
        if (selesaikanMazeRekursif(maze, x, y - 1, sol)) return true; // KIRI

        // BACKTRACKING
        sol[x][y] = 0;
        return false;
    }
    return false;
}

int main() {
    cout << "Masukkan ukuran dimensi labirin N: ";
    cin >> N;

    vector<vector<int>> maze(N, vector<int>(N));
    vector<vector<int>> sol(N, vector<int>(N, 0));

    cout << "Masukkan nilai matriks labirin:\n";
    for (int i = 0; i < N; i++) {
        for (int j = 0; j < N; j++) {
            cin >> maze[i][j];
        }
    }

    if (selesaikanMazeRekursif(maze, 0, 0, sol)) {
        cetakSolusi(sol);
    } else {
        cout << "\n[!] Tidak ada jalur solusi.\n";
    }

    return 0;
}
