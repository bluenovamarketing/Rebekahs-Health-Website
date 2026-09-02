using System;
using System.IO;
using System.Linq;
using System.Threading;
using Windows.Foundation;
using Windows.Media.SpeechSynthesis;
using Windows.Storage.Streams;

internal static class MarkOneCoreTts
{
    [STAThread]
    private static void Main(string[] args)
    {
        if (args.Length != 2)
        {
            Console.Error.WriteLine("Usage: mark-onecore-tts.exe <text-file> <wav-file>");
            Environment.Exit(2);
        }

        Render(args[0], args[1]);
    }

    private static void Render(string textPath, string outputPath)
    {
        string narration = File.ReadAllText(textPath).Trim();
        if (narration.Length == 0) throw new InvalidOperationException("Narration text is empty.");

        using (var synthesizer = new SpeechSynthesizer())
        {
            var mark = SpeechSynthesizer.AllVoices.FirstOrDefault(
                voice => voice.DisplayName.IndexOf("Mark", StringComparison.OrdinalIgnoreCase) >= 0
                    && voice.Language.StartsWith("en-US", StringComparison.OrdinalIgnoreCase));
            if (mark == null) throw new InvalidOperationException("Microsoft Mark was not found in the installed OneCore voices.");
            synthesizer.Voice = mark;

            var synthesis = synthesizer.SynthesizeTextToStreamAsync(narration);
            while (synthesis.Status == AsyncStatus.Started) Thread.Sleep(10);
            if (synthesis.Status == AsyncStatus.Error) throw synthesis.ErrorCode;

            using (SpeechSynthesisStream stream = synthesis.GetResults())
            using (IInputStream input = stream.GetInputStreamAt(0))
            using (var reader = new DataReader(input))
            {
                uint byteCount = checked((uint)stream.Size);
                var loading = reader.LoadAsync(byteCount);
                while (loading.Status == AsyncStatus.Started) Thread.Sleep(10);
                if (loading.Status == AsyncStatus.Error) throw loading.ErrorCode;
                loading.GetResults();
                var bytes = new byte[byteCount];
                reader.ReadBytes(bytes);
                File.WriteAllBytes(outputPath, bytes);
            }
        }
    }
}
