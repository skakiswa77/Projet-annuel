import java.io.FileOutputStream;
import java.io.IOException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.Scanner;
import org.json.JSONArray;
import org.json.JSONObject;

import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Element;
import com.itextpdf.text.Font;
import com.itextpdf.text.FontFactory;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfPTable;
import com.itextpdf.text.pdf.PdfWriter;
import com.itextpdf.text.Image;

import org.jfree.chart.ChartFactory;
import org.jfree.chart.JFreeChart;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.data.category.DefaultCategoryDataset;
import org.jfree.data.general.DefaultPieDataset;
import org.jfree.chart.ChartUtilities;

public class BusinessCareStatsReport {
    
    private static final String API_BASE_URL = "http://localhost/businesscare/api";
    
    public static void main(String[] args) {
        try {
 
            String statsData = getDataFromAPI("/stats.php");
            

            JSONObject statsJson = new JSONObject(statsData);
            

            generateReport(statsJson);
            
            System.out.println("Le rapport a été généré avec succès !");
        } catch (Exception e) {
            System.out.println("Erreur lors de la génération du rapport : " + e.getMessage());
            e.printStackTrace();
        }
    }
    
    private static String getDataFromAPI(String endpoint) throws IOException {
        URL url = new URL(API_BASE_URL + endpoint);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");
        conn.connect();
        
        int responseCode = conn.getResponseCode();
        if (responseCode != 200) {
            throw new RuntimeException("Erreur HTTP : " + responseCode);
        }
        
        Scanner scanner = new Scanner(url.openStream());
        StringBuilder response = new StringBuilder();
        
        while (scanner.hasNextLine()) {
            response.append(scanner.nextLine());
        }
        
        scanner.close();
        return response.toString();
    }
    private static void generateReport(JSONObject statsJson) throws DocumentException, IOException {
        Document document = new Document();
        PdfWriter.getInstance(document, new FileOutputStream("BusinessCareStats.pdf"));
        document.open();
        
        Font titleFont = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 20);
        document.add(new Paragraph("Rapport Statistique Business Care", titleFont));
        document.add(new Paragraph("Date : " + new java.util.Date()));
        document.add(new Paragraph(" "));
        
        addCompanyStats(document, statsJson.getJSONObject("companies"));
        
        document.newPage();
        addEventStats(document, statsJson.getJSONObject("events"));
        
        document.newPage();
        addProviderStats(document, statsJson.getJSONObject("providers"));
        
        document.close();
    }

    private static void addCompanyStats(Document document, JSONObject data) throws DocumentException, IOException {
        Font sectionFont = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 16);
        document.add(new Paragraph("1. Statistiques des Sociétés Clientes", sectionFont));
        document.add(new Paragraph(" "));
        
        document.add(new Paragraph("Nombre total de sociétés : " + data.getInt("total_companies")));
        document.add(new Paragraph(" "));
        
        if (data.has("subscription_distribution")) {
            Image chart1 = createPieChart(
                data.getJSONArray("subscription_distribution"),
                "plan_type", "total",
                "Répartition par Type d'Abonnement"
            );
            document.add(chart1);
            document.add(new Paragraph(" "));
        }
        

        if (data.has("top_companies_by_employees")) {
            Image chart2 = createBarChart(
                data.getJSONArray("top_companies_by_employees"),
                "name", "employee_count",
                "Top 5 Sociétés par Nombre d'Employés",
                "Société", "Nombre d'Employés"
            );
            document.add(chart2);
            document.add(new Paragraph(" "));
        }

        if (data.has("companies_by_month")) {
            Image chart3 = createLineChart(
                data.getJSONArray("companies_by_month"),
                "month", "total_companies",
                "Sociétés Créées par Mois",
                "Mois", "Nombre de Sociétés"
            );
            document.add(chart3);
            document.add(new Paragraph(" "));
        }
    }
    

    private static void addEventStats(Document document, JSONObject data) throws DocumentException, IOException {

        Font sectionFont = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 16);
        document.add(new Paragraph("2. Statistiques des Événements", sectionFont));
        document.add(new Paragraph(" "));
        
        if (data.has("upcoming_events")) {
            document.add(new Paragraph("Nombre d'événements à venir : " + data.getInt("upcoming_events")));
            document.add(new Paragraph(" "));
        }

        if (data.has("events_by_type")) {
            Image chart1 = createPieChart(
                data.getJSONArray("events_by_type"),
                "event_type", "total",
                "Répartition par Type d'Événement"
            );
            document.add(chart1);
            document.add(new Paragraph(" "));
        }
        
        if (data.has("attendance_by_type")) {
            Image chart3 = createBarChart(
                data.getJSONArray("attendance_by_type"),
                "event_type", "attendance_rate",
                "Taux de Participation par Type d'Événement",
                "Type d'Événement", "Taux de Participation (%)"
                );
            document.add(chart3);
            document.add(new Paragraph(" "));
        }
        
        if (data.has("events_by_month")) {
            Image chart4 = createLineChart(
                data.getJSONArray("events_by_month"),
                "month", "total_events",
                "Événements par Mois",
                "Mois", "Nombre d'Événements"
            );
            document.add(chart4);
            document.add(new Paragraph(" "));
        }
    }
    

    private static void addProviderStats(Document document, JSONObject data) throws DocumentException, IOException {

        Font sectionFont = FontFactory.getFont(FontFactory.HELVETICA_BOLD, 16);
        document.add(new Paragraph("3. Statistiques des Prestataires", sectionFont));
        document.add(new Paragraph(" "));

        if (data.has("total_providers")) {
            document.add(new Paragraph("Nombre total de prestataires : " + data.getInt("total_providers")));
            document.add(new Paragraph(" "));
        }

        if (data.has("verification_status")) {
            JSONObject verificationStatus = data.getJSONObject("verification_status");
            DefaultPieDataset dataset = new DefaultPieDataset();
            dataset.setValue("Vérifiés", verificationStatus.getInt("verified"));
            dataset.setValue("Non vérifiés", verificationStatus.getInt("not_verified"));
            
            JFreeChart chart = ChartFactory.createPieChart(
                "Prestataires Vérifiés vs Non Vérifiés",
                dataset,
                true,
                true,
                false
            );
            
            java.io.File chartFile = java.io.File.createTempFile("chart", ".png");
            ChartUtilities.saveChartAsPNG(chartFile, chart, 500, 300);
            
            Image image = Image.getInstance(chartFile.getAbsolutePath());
            image.scaleToFit(500, 300);
            image.setAlignment(Element.ALIGN_CENTER);
            document.add(image);
            document.add(new Paragraph(" "));
        }
        
        if (data.has("providers_by_specialization")) {
            Image chart1 = createPieChart(
                data.getJSONArray("providers_by_specialization"),
                "specialization", "total",
                "Répartition par Spécialisation"
            );
            document.add(chart1);
            document.add(new Paragraph(" "));
        }
        
    }
    
    private static Image createPieChart(JSONArray data, String labelField, String valueField, String title) throws IOException {
        DefaultPieDataset dataset = new DefaultPieDataset();
        
        for (int i = 0; i < data.length(); i++) {
            JSONObject item = data.getJSONObject(i);
            dataset.setValue(item.getString(labelField), item.getDouble(valueField));
        }
        
        JFreeChart chart = ChartFactory.createPieChart(
            title,
            dataset,
            true,
            true,
            false
        );
        
        java.io.File chartFile = java.io.File.createTempFile("chart", ".png");
        ChartUtilities.saveChartAsPNG(chartFile, chart, 500, 300);
        
        Image image = Image.getInstance(chartFile.getAbsolutePath());
        image.scaleToFit(500, 300);
        image.setAlignment(Element.ALIGN_CENTER);
        
        return image;
    }
    
    private static Image createBarChart(JSONArray data, String categoryField, String valueField, String title, String xLabel, String yLabel) throws IOException {
        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        
        for (int i = 0; i < data.length(); i++) {
            JSONObject item = data.getJSONObject(i);
            String category = item.getString(categoryField);
            
            if (category.length() > 15) {
                category = category.substring(0, 12) + "...";
            }
            
            dataset.addValue(item.getDouble(valueField), "", category);
        }
        
        JFreeChart chart = ChartFactory.createBarChart(
            title,
            xLabel,
            yLabel,
            dataset,
            PlotOrientation.VERTICAL,
            false,
            true,
            false
        );
        
        java.io.File chartFile = java.io.File.createTempFile("chart", ".png");
        ChartUtilities.saveChartAsPNG(chartFile, chart, 500, 300);
        
        Image image = Image.getInstance(chartFile.getAbsolutePath());
        image.scaleToFit(500, 300);
        image.setAlignment(Element.ALIGN_CENTER);
        
        return image;
    }
    
    private static Image createLineChart(JSONArray data, String categoryField, String valueField, String title, String xLabel, String yLabel) throws IOException {
        DefaultCategoryDataset dataset = new DefaultCategoryDataset();
        
        for (int i = 0; i < data.length(); i++) {
            JSONObject item = data.getJSONObject(i);
            String category;
            
            if (item.has("year") && item.has("month")) {
                category = item.getInt("year") + "/" + item.getInt("month");
            } else {
                category = item.getString(categoryField);
            }
            
            dataset.addValue(item.getDouble(valueField), "", category);
        }
        
        JFreeChart chart = ChartFactory.createLineChart(
            title,
            xLabel,
            yLabel,
            dataset,
            PlotOrientation.VERTICAL,
            false,
            true,
            false
        );
        
        java.io.File chartFile = java.io.File.createTempFile("chart", ".png");
        ChartUtilities.saveChartAsPNG(chartFile, chart, 500, 300);
        
        Image image = Image.getInstance(chartFile.getAbsolutePath());
        image.scaleToFit(500, 300);
        image.setAlignment(Element.ALIGN_CENTER);
        
        return image;
    }
}